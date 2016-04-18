<?php
use Pentagonal\StaticHelper\FilterHelper;

/**
 * Class TestFilter
 * PHPUnit Test FrameWork Case
 */
class FilterTest extends \PHPUnit_Framework_TestCase
{
    public function testZeroize()
    {
        $this->assertNotNull(FilterHelper::zeroise(1, 2));
    }

    public function testAntiSpamBot()
    {
        $this->assertNotEquals(
            'email@example.com',
            FilterHelper::antiSpamBot('email@example.com')
        );
    }

    public function testMultibyteEntities()
    {
        $chinesseString = 'PHP单位工作';
        $this->assertNotEquals(
            $chinesseString,
            FilterHelper::multibyteEntities($chinesseString, false)
        );
    }

    public function testForceBalanceTags()
    {
        $invalidMarkup = '<p>Paragraph';
        $this->assertNotEquals(
            $invalidMarkup,
            FilterHelper::forceBalanceTags($invalidMarkup)
        );
    }

    public function testSeemsUtf8()
    {
        $utf8 = 'PHP单位工作';
        $this->assertTrue(FilterHelper::seemsUtf8($utf8));
    }

    public function testRemoveAccents()
    {
        $accents = 'ĀŞĶ';
        $accentsConvert = 'ASK';
        $this->assertNotEquals(
            $accents,
            FilterHelper::removeAccents($accents)
        );
        $this->assertEquals(
            $accentsConvert,
            FilterHelper::removeAccents($accents)
        );
    }

    public function testEmailUTF8()
    {
        $emailInvalid = 'admin@localhost';
        $emailvalid = 'admin@example.com';
        $this->assertFalse(
            FilterHelper::emailUTF8($emailInvalid)
        );
        $this->assertEquals(
            $emailvalid,
            FilterHelper::emailUTF8($emailvalid)
        );
    }

    public function testFilterForUsername()
    {
        $invalid = 'us';
        $valid = 'username';
        $this->assertFalse(
            FilterHelper::filterForUsername($invalid)
        );
        $this->assertEquals(
            $valid,
            FilterHelper::filterForUsername($valid)
        );
    }

    public function testEscapeSingleQuote()
    {
        $string = "Helo' there";
        $this->assertNotEquals(
            $string,
            FilterHelper::escapeSingleQuote($string)
        );
        $this->assertStringStartsWith(
            "Helo\'",
            FilterHelper::escapeSingleQuote($string)
        );
    }

    public function testEscapeDoubleQuote()
    {
        $string = 'Helo" there';
        $this->assertNotEquals(
            $string,
            FilterHelper::escapeDoubleQuote($string)
        );
        $this->assertStringStartsWith(
            'Helo\"',
            FilterHelper::escapeDoubleQuote($string)
        );
    }

    public function testSplitCrossDomain()
    {
        $domain = 'http://www.example.com';
        $domainCookieCross = '.example.com';
        $this->assertNotEquals(
            $domain,
            FilterHelper::splitCrossDomain($domain)
        );
        $this->assertEquals(
          $domainCookieCross,
            FilterHelper::splitCrossDomain($domain)
        );
    }
}
