<?php
namespace Tiemez\HtmlPurifier\Test\TestCase\Lib;

use Cake\TestSuite\TestCase;

use Tiemez\HtmlPurifier\Lib\Purifier;

/**
 * PurifierTest
 */
class PurifierTest extends TestCase {

    public function setUp(): void
    {
        parent::setUp();

        Purifier::config('default', [
            'HTML.AllowedElements' => 'a, em, blockquote, p, strong, pre, code, span,ul,ol,li,img',
            'HTML.AllowedAttributes' => 'a.href, a.title, img.src, img.alt, *.style',
            'CSS.AllowedProperties' => 'text-decoration',
            'HTML.TidyLevel' => 'heavy',
            'HTML.Doctype' => 'XHTML 1.0 Transitional'
        ]);
    }

    /**
     * getPurifierInstance
     *
     * @return void
     */
    public function testConfig()
    {
        $this->assertInstanceOf('HTMLPurifier_Config', Purifier::config('default'));
    }

    /**
     * @return void
     */
    public function testConfigInvalidArgumentException()
    {
        $this->expectException(\InvalidArgumentException::class);
        Purifier::config('does-not-exist');
    }

    /**
     * getPurifierInstance
     *
     * @return void
     */
    public function testGetPurifierInstance()
    {
        $this->assertInstanceOf('HTMLPurifier', Purifier::getPurifierInstance('default'));
    }

    /**
     * @return void
     */
    public function testGetPurifierInstanceInvalidArgumentException()
    {
        $this->expectException(\InvalidArgumentException::class);
        Purifier::getPurifierInstance('does-not-exist');
    }

    /**
     * testPurifyHtml
     *
     * @return void
     */
    public function testPurifyHtml()
    {
        $html = '<p style="font-weight: bold;"><script>alert("alert!");</script><span style="text-decoration: line-through;" _mce_style="text-decoration: line-through;">shsfhshs</span></p><p><strong>sdhsdhds</strong></p><p><em>shsdh</em><span style="text-decoration: underline;" _mce_style="text-decoration: underline;">dsh</span></p><ul><li>sdgsgssgd</li><li>sdgdsg</li><li>sdgsdgsg</li><li>sdgdg<br></li></ul>';
        $expected = '<p><span style="text-decoration:line-through;">shsfhshs</span></p><p><strong>sdhsdhds</strong></p><p><em>shsdh</em><span style="text-decoration:underline;">dsh</span></p><ul><li>sdgsgssgd</li><li>sdgdsg</li><li>sdgsdgsg</li><li>sdgdg</li></ul>';
        $result = Purifier::clean($html);
        $this->assertEquals($result, $expected);
    }
}
