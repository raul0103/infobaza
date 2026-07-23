<?php

namespace App\Support;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\MarkdownConverter;

class Markdown
{
    private static ?MarkdownConverter $converter = null;

    public static function parse(?string $text): HtmlString
    {
        if ($text === null || trim($text) === '') {
            return new HtmlString('');
        }

        return new HtmlString(self::converter()->convert($text)->getContent());
    }

    public static function converter(): MarkdownConverter
    {
        if (self::$converter) {
            return self::$converter;
        }

        $environment = new Environment([
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
            'max_nesting_level' => 20,
        ]);

        $environment->addExtension(new CommonMarkCoreExtension);
        $environment->addExtension(new GithubFlavoredMarkdownExtension);

        return self::$converter = new MarkdownConverter($environment);
    }

    public static function registerBlade(): void
    {
        Blade::directive('markdown', function (string $expression) {
            return "<?php echo \\App\\Support\\Markdown::parse($expression); ?>";
        });
    }
}
