<?php


use PhpModules\NamespaceName;
use PHPUnit\Framework\TestCase;

class NamespaceNameTest extends TestCase
{

    public function test_isParentOf_shouldReturnTrueWhenEqual()
    {
        /* Given */
        $namespaceName = new NamespaceName('A');
        $otherNamespaceName = new NamespaceName('A');

        /* When */
        $result = $namespaceName->isParentOf($otherNamespaceName);

        /* Then */
        $this->assertTrue($result);
    }

    public function test_isParentOf_shouldReturnFalseWhenNotEqual()
    {
        /* Given */
        $namespaceName = new NamespaceName('A');
        $otherNamespaceName = new NamespaceName('B');

        /* When */
        $result = $namespaceName->isParentOf($otherNamespaceName);

        /* Then */
        $this->assertFalse($result);
    }

    public function test_isParentOf_shouldReturnTrueWhenChild()
    {
        /* Given */
        $namespaceName = new NamespaceName('A');
        $otherNamespaceName = new NamespaceName('A\B');

        /* When */
        $result = $namespaceName->isParentOf($otherNamespaceName);

        /* Then */
        $this->assertTrue($result);
    }

    public function test_isParentOf_shouldReturnFalseWhenParent()
    {
        /* Given */
        $namespaceName = new NamespaceName('A\B');
        $otherNamespaceName = new NamespaceName('A');

        /* When */
        $result = $namespaceName->isParentOf($otherNamespaceName);

        /* Then */
        $this->assertFalse($result);
    }

}
