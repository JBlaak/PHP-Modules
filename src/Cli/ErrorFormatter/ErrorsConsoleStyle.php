<?php

namespace PhpModules\Cli\ErrorFormatter;

use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Terminal;

class ErrorsConsoleStyle extends SymfonyStyle
{

    /**
     * @param string[] $headers
     * @param string[][] $rows
     */
    public function table(array $headers, array $rows): void
    {
        $terminalWidth = (new Terminal())->getWidth() - 2;
        $maxHeaderWidth = strlen($headers[0]);
        foreach ($rows as $row) {
            $length = strlen($row[0]);
            if ($maxHeaderWidth !== 0 && $length <= $maxHeaderWidth) {
                continue;
            }

            $maxHeaderWidth = $length;
        }

        $wrap = static fn($rows): array => array_map(static fn($row): array => array_map(static function ($s) use ($terminalWidth, $maxHeaderWidth) {
            if ($terminalWidth > $maxHeaderWidth + 5) {
                return wordwrap(
                    $s,
                    $terminalWidth - $maxHeaderWidth - 5,
                    "\n",
                    true,
                );
            }

            return $s;
        }, $row), $rows);

        parent::table($headers, $wrap($rows));
    }
}
