<?php

namespace CacheBundle\Document\Extractor;

use PHPUnit\Framework\TestCase;
use UserBundle\Enum\ThemeOptionExtractEnum;

/**
 * Class BasicDocumentContentExtractorTest
 * @package CacheBundle\Document\Extractor
 */
class BasicDocumentContentExtractorTest extends TestCase
{

    const SIMPLE_TEXT = <<<EOT
Outside a character class, in the default matching mode, the circumflex character (^) is an assertion which is true only if the current matching point is at the start of the subject string. Inside a character class, circumflex (^) has an entirely different meaning (see below).

Circumflex (^) need not be the first character of the pattern if a number of alternatives are involved, but it should be the first thing in each alternative in which it appears if the pattern is ever to match that branch. If all possible alternatives start with a circumflex (^), that is, if the pattern is constrained to match only at the start of the subject, it is said to be an "anchored" pattern. (There are also other constructs that can cause a pattern to be anchored.)

A dollar character ($) is an assertion which is TRUE only if the current matching point is at the end of the subject string, or immediately before a newline character that is the last character in the string (by default). Dollar ($) need not be the last character of the pattern if a number of alternatives are involved, but it should be the last item in any branch in which it appears. Dollar has no special meaning in a character class.

The meaning of dollar can be changed so that it matches only at the very end of the string, by setting the PCRE_DOLLAR_ENDONLY option at compile or matching time. This does not affect the \Z assertion.

The meanings of the circumflex and dollar characters are changed if the PCRE_MULTILINE option is set. When this is the case, they match immediately after and immediately before an internal "\n" character, respectively, in addition to matching at the start and end of the subject string. For example, the pattern /^abc$/ matches the subject string "def\nabc" in multiline mode, but not otherwise. Consequently, patterns that are anchored in single line mode because all branches start with "^" are not anchored in multiline mode. The PCRE_DOLLAR_ENDONLY option is ignored if PCRE_MULTILINE is set.

Note that the sequences \A, \Z, and \z can be used to match the start and end of the subject in both modes, and if all branches of a pattern start with \A is it always anchored, whether PCRE_MULTILINE is set or not. Cat also been here.
EOT;

    const ARABIC_TEXT = <<<EOT
???? ?????? ?????????? ????????????????, ?????? ???????? ???????????? ????. ?????? ?????????? ?????????????? ???????????????? ????, ???????? ?????????? ?????????? ?????? ????, ?????? ???? ?????????????? ????????????????. ???? ?????? ???????????? ?????????????? ??????????????, ???? ???????????? ?????????????? ?????????????? ??????. ???????? ???????????? ???????????? ?????? ??, ?????? ???? ?????????? ?????????? ??????????????. ???????? ?????????? ?????? ????, ???????? ?????????????? ?????? ????. ???????? ???????? ???????????? ???? ??????.

???????????????? ?????????????????? ?????? ????, ???? ?????? ?????????? ?????????????? ??????????????????. ?????????? ?????????? ?????????????? ?????? ????, ???? ???????? ?????????? ?????????? ??????. ?????? ?????????????? ???????????????????? ????, ???? ???????????? ???????????? ?????????????? ??????. ?????? ???? ???????? ???????? ????????????. ?????????????? ?????????????? ???? ??????, ?????? ???? ???????? ?????????? ??????????. ???????? ?????????? ?????? ????.

?? ?????????? ???????????? ?????????????? ??????, ???????? ???????????? ???????????????? ???? ??????, ???? ?????? ?????????? ?????????? ????????????????. ?? ?????? ?????????????? ??????????????????, ?????????? ???????????????? ???????????????? ???? ??????, ?????????? ?????????????? ???????????????? ?????? ????. ?????? ???? ?????????? ??????????. ?????????? ???????????? ???? ??????, ?????????? ???????????????? ???? ??????. ?????? ???????? ???????????????????? ????.

???? ???????? ???????????? ???????????? ??????. ???? ?????? ?????????? ??????????????, ?????????? ?????????? ???? ??????, ???? ???????? ?????????? ?????????????? ??????. ???? ?????? ???????????? ??????????????, ???? ???????? ???????????? ?????????????? ??????. ???? ?????? ?????????? ??????????.

???? ?????? ???????? ?????????? ??????????????, ???? ?????? ???????????? ??????????????. ???????????????? ???????????????????? ???? ??????. ?????? ???? ???????? ????????, ???? ?????? ?????????? ????????????. ???? ???????? ?????????????? ??????, ???? ?????? ?????????? ??????????????. ???? ?????? ???????? ??????????????.

?????? ?????????? ?????????? ???????????? ????. ?????? ???????? ???????????? ????. ???????????? ???????????? ?????????????? ???? ??????. ???????? ?????????? ???????????????? ?????? ????, ?????? ?????????? ???????????? ????. ???? ???????? ?????????????? ??????, ???? ???????? ?????????????? ?????????????? ??????.

?????????? ?????????????????? ???? ??????, ?????? ???? ?????????? ?????????? ??????????????. ???? ?????? ???????????? ????????????????. ?????????? ?????????????? ???????????????? ???? ??????, ?????????? ?????????????? ?????? ????. ?????? ???? ?????????? ??????????. ?????? ???????? ?????????? ????, ?????? ???? ???????????? ????????????.

?????? ???? ?????????? ???????????? ????????????, ?????? ???? ???????????????? ????????????????. ???? ?????? ???????? ?????????????? ??????????????, ???????? ?????????? ???????????????? ?????? ????. ???? ?????????? ???????????? ???????????? ??????, ???? ?????? ???????? ????????????, ???????????? ???????????? ???? ??????. ?????????????? ?????????????? ?? ??????. ?????? ???? ???????? ??????????, ????????????
EOT;

    const HIEROGLYPHIC_TEXT = <<<EOT
?????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????

?????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????

????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????

??????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????

?????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????

????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????

?????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????

????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????

????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????

?????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????
EOT;


    /**
     * @return void
     */
    public function testExtractNo()
    {
        $extractor = new BasicDocumentContentExtractor(100, 100);

        $queries = [
            'saw',
            'Note',
            'sequences',
        ];

        foreach ($queries as $query) {
            $actual = $extractor->extract(
                self::SIMPLE_TEXT,
                $query,
                ThemeOptionExtractEnum::no()
            );

            $this->assertEquals('', $actual->getText());
        }

        $queries = [
            '????????????',
            '??????????????????',
            '??????????',
        ];

        foreach ($queries as $query) {
            $actual = $extractor->extract(
                self::ARABIC_TEXT,
                $query,
                ThemeOptionExtractEnum::no()
            );

            $this->assertEquals('', $actual->getText());
        }

        $queries = [
            '?????????',
            '?????????',
            '????????????',
        ];

        foreach ($queries as $query) {
            $actual = $extractor->extract(
                self::HIEROGLYPHIC_TEXT,
                $query,
                ThemeOptionExtractEnum::no()
            );

            $this->assertEquals('', $actual->getText());
        }
    }

    /**
     * @dataProvider startExtractProvider
     *
     * @param string  $text            Document content text.
     * @param string  $query           Search query.
     * @param integer $startExtractLen How many characters extract from beginning
     *                                 of content.
     * @param string  $expected        Expected result.
     *
     * @return void
     */
    public function testExtractStart($text, $query, $startExtractLen, $expected)
    {
        $extractor = new BasicDocumentContentExtractor($startExtractLen, random_int(0, 100));
        $actual = $extractor->extract($text, $query, ThemeOptionExtractEnum::start());

        $this->assertEquals($expected, $actual->getText());
    }

    /**
     * @return array
     */
    public function startExtractProvider()
    {
        return [
            [
                self::SIMPLE_TEXT,
                'cat',
                20,
                'Outside a character ',
            ],
            [
                self::SIMPLE_TEXT,
                'some',
                1,
                'O',
            ],
            [
                self::SIMPLE_TEXT,
                '',
                100000000,
                self::SIMPLE_TEXT,
            ],
            [
                self::SIMPLE_TEXT,
                'long query',
                0,
                '',
            ],
            [
                self::ARABIC_TEXT,
                '',
                25,
                '???? ?????? ?????????? ????????????????, ????',
            ],
            [
                self::ARABIC_TEXT,
                '',
                1,
                '??',
            ],
            [
                self::ARABIC_TEXT,
                '',
                10000000,
                self::ARABIC_TEXT,
            ],
            [
                self::HIEROGLYPHIC_TEXT,
                '',
                25,
                '???????????????????????????????????????????????????????????????????????????',
            ],
            [
                self::HIEROGLYPHIC_TEXT,
                '',
                1,
                '???',
            ],
            [
                self::HIEROGLYPHIC_TEXT,
                '',
                10000000,
                self::HIEROGLYPHIC_TEXT,
            ],
        ];
    }

    /**
     * @dataProvider contextExtractProvider
     *
     * @param string  $text              Document content text.
     * @param string  $query             Search query.
     * @param integer $contextExtractLen How many characters extract before and
     *                                   after search keyword.
     * @param string  $expected          Expected result.
     *
     * @return void
     */
    public function testExtractContext($text, $query, $contextExtractLen, $expected)
    {
        $extractor = new BasicDocumentContentExtractor(random_int(0, 100), $contextExtractLen);
        $actual = $extractor->extract($text, $query, ThemeOptionExtractEnum::context());

        $this->assertEquals($expected, $actual->getText());
    }

    /**
     * @return array
     */
    public function contextExtractProvider()
    {
        return [
            [
                self::SIMPLE_TEXT,
                'mode',
                5,
                'hing mode, the',
            ],
            [
                self::SIMPLE_TEXT,
                'mode',
                0,
                'mode',
            ],
            [
                self::SIMPLE_TEXT,
                'character mode',
                25,
                'Outside a character class, in the default ma',
            ],
            [
                self::SIMPLE_TEXT,
                'cat PCRE_MULTILINE dog',
                7,
                'if the PCRE_MULTILINE option',
            ],
            [
                self::SIMPLE_TEXT,
                'mode Outside PCRE_MULTILINE',
                1000000000,
                self::SIMPLE_TEXT,
            ],
            [
                self::SIMPLE_TEXT,
                'cat',
                25,
                'MULTILINE is set or not. Cat also been here.',
            ],
            [
                self::ARABIC_TEXT,
                '???????????????? ??????',
                25,
                '???? ?????? ?????????? ????????????????, ?????? ???????? ???????????? ????. ??????',
            ],
            [
                self::ARABIC_TEXT,
                '????????????????',
                0,
                '????????????????',
            ],
            [
                self::ARABIC_TEXT,
                '????????????????',
                10000000,
                self::ARABIC_TEXT,
            ],
            [
                self::HIEROGLYPHIC_TEXT,
                '????????? ?????????????????? ???????????????',
                25,
                '????????????????????????????????????????????????????????????????????????????????????????????????????????????',
            ],
            [
                self::HIEROGLYPHIC_TEXT,
                '?????????',
                0,
                '?????????',
            ],
            [
                self::HIEROGLYPHIC_TEXT,
                '????????? ??????????????????',
                10000000,
                self::HIEROGLYPHIC_TEXT,
            ],
        ];
    }

    /**
     * @return void
     */
    public function testExtractEmptyContent()
    {
        $extractor = new BasicDocumentContentExtractor(100, 100);

        $this->assertEquals('', $extractor->extract('', 'mode', ThemeOptionExtractEnum::no())->getText());
        $this->assertEquals('', $extractor->extract('', 'mode', ThemeOptionExtractEnum::start())->getText());
        $this->assertEquals('', $extractor->extract('', 'mode', ThemeOptionExtractEnum::context())->getText());
    }
}
