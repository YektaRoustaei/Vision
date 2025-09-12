<?php

namespace LaravelVision\Drivers\Video;

use LaravelVision\Contracts\VideoProcessor;
use RuntimeException;

class FfmpegVideoProcessor implements VideoProcessor
{
    protected string $ffmpeg;
    protected string $ffprobe;
    protected int $timeout;

    public function __construct()
    {
        $cfg = config('vision.video.ffmpeg');
        $this->ffmpeg = $cfg['binary'] ?? 'ffmpeg';
        $this->ffprobe = $cfg['ffprobe_binary'] ?? 'ffprobe';
        $this->timeout = (int) ($cfg['timeout'] ?? 60);
    }

    public function open(string $path): mixed
    {
        if (!is_file($path)) {
            throw new RuntimeException("Video not found: {$path}");
        }
        return $path;
    }

    public function save(mixed $video, string $path): bool
    {
        if (is_string($video)) {
            return copy($video, $path);
        }
        throw new RuntimeException('Unsupported video handle');
    }

    public function extractFrame(string $path, float $seconds, string $outputPath): bool
    {
        $ss = max(0, $seconds);
        $cmd = sprintf('%s -y -ss %s -i %s -frames:v 1 %s',
            escapeshellcmd($this->ffmpeg),
            escapeshellarg((string) $ss),
            escapeshellarg($path),
            escapeshellarg($outputPath)
        );
        return $this->run($cmd);
    }

    public function transcode(string $path, string $outputPath, array $options = []): bool
    {
        $vcodec = $options['vcodec'] ?? 'libx264';
        $crf = (int) ($options['crf'] ?? 23);
        $preset = $options['preset'] ?? 'medium';
        $cmd = sprintf('%s -y -i %s -c:v %s -preset %s -crf %d -c:a copy %s',
            escapeshellcmd($this->ffmpeg),
            escapeshellarg($path),
            escapeshellarg($vcodec),
            escapeshellarg($preset),
            $crf,
            escapeshellarg($outputPath)
        );
        return $this->run($cmd);
    }

    protected function run(string $cmd): bool
    {
        $descriptor = [1 => ['pipe', 'w'], 2 => ['pipe', 'w']];
        $proc = proc_open($cmd, $descriptor, $pipes);
        if (!is_resource($proc)) {
            return false;
        }
        $start = time();
        $output = '';
        $error = '';
        foreach ([1,2] as $i) {
            stream_set_blocking($pipes[$i], false);
        }
        while (true) {
            $status = proc_get_status($proc);
            $output .= stream_get_contents($pipes[1]);
            $error .= stream_get_contents($pipes[2]);
            if (!$status['running']) {
                break;
            }
            if ((time() - $start) > $this->timeout) {
                proc_terminate($proc, 9);
                foreach ($pipes as $p) { fclose($p); }
                return false;
            }
            usleep(100000);
        }
        foreach ($pipes as $p) { fclose($p); }
        $code = proc_close($proc);
        return $code === 0;
    }
}


