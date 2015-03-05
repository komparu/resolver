<?php

use Komparu\Resolver\Resolver;

class TagsTest extends PHPUnit_Framework_TestCase
{
    /**
     * @param array $tags
     * @param array $match
     * @param array $expected
     * @dataProvider provideTags
     */
    public function testTags(Array $tags, Array $match, Array $expected = [])
    {
        $resolver = new Resolver;
        $resolver->register('test', function() {
            return 'success';
        });

        $resolver->tag('test', $tags);

        $hello = $resolver->tagged($match);
        $this->assertSame($expected, $hello);
    }

    /**
     * @return array
     */
    public function provideTags()
    {
        return [

            // These tags should match
            [['foo'], ['foo'], ['success']],
            [['foo', 'bar'], ['foo'], ['success']],
            [['foo', 'bar'], ['bar'], ['success']],
            [['foo', 'bar'], ['foo', 'bar'], ['success']],
            [['foo' => true, 'bar' => false], ['foo' => true], ['success']],
            [['foo' => true, 'bar' => false], ['foo' => true, 'bar' => false], ['success']],
            [['index' => 'foo', 'type' => 'bar'], ['index' => ['foo', 'bar']], ['success']],

            // These tags should not match
            [['foo'], ['foo2']],
            [['foo', 'bar'], ['foo2']],
            [['foo', 'bar'], ['foo', 'bar1']],
            [['foo', 'bar'], ['foo2', 'bar']],
            [['foo' => true, 'bar' => false], ['foo2' => true, 'bar' => false]],
            [['foo' => true, 'bar' => false], ['foo' => false, 'bar' => false]],
        ];
    }

}