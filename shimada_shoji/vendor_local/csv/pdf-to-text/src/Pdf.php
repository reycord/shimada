<?php

namespace Csv\PdfToText;

use Csv\PdfToText\Exceptions\CouldNotExtractText;
use Csv\PdfToText\Exceptions\PdfNotFound;
use Symfony\Component\Process\Process;

class Pdf
{
    protected $pdf;

    protected $binPath;

    protected $options = [];

    public function __construct($binPath = null)
    {
        $this->binPath = $binPath ? $binPath : '/usr/bin/pdftotext';
    }

    public function setPdf($pdf)
    {
        if (!is_readable($pdf)) {
            throw new PdfNotFound(sprintf('could not find or read pdf `%s`', $pdf));
        }

        $this->pdf = $pdf;

        return $this;
    }

    public function setOptions(array $options)
    {
        $mapper = function ($content) {
            $content = trim($content);
            if ('-' !== ($content[0] ? $content[0] : '')) {
                $content = '-'.$content;
            }

            return explode(' ', $content, 2);
        };

        $reducer = function (array $carry, array $option) {
            return array_merge($carry, $option);
        };

        $this->options = array_reduce(array_map($mapper, $options), $reducer, []);

        return $this;
    }

    public function text()
    {
        $process = new Process(array_merge([$this->binPath], $this->options, [$this->pdf, '-']));
        $process->run();
        if (!$process->isSuccessful()) {
            throw new CouldNotExtractText($process);
        }

        return trim($process->getOutput(), " \t\n\r\0\x0B\x0C");
    }

    public static function getText(string $pdf, string $binPath = null, array $options = [])
    {
        return (new static($binPath))
            ->setOptions($options)
            ->setPdf($pdf)
            ->text()
        ;
    }
}
