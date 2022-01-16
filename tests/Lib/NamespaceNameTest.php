<?php


namespace Lib;

use PhpModules\Lib\Domain\NamespaceName;
use PHPUnit\Framework\TestCase;

class NamespaceNameTest extends TestCase
{

    public function test_isParentOf_shouldReturnTrueWhenEqual(): void
    {
        /* Given */
        $namespaceName = new NamespaceName('A');
        $otherNamespaceName = new NamespaceName('A');

        /* When */
        $result = $namespaceName->isParentOf($otherNamespaceName);

        /* Then */
        $this->assertTrue($result);
    }

    public function test_isParentOf_shouldReturnFalseWhenNotEqual(): void
    {
        /* Given */
        $namespaceName = new NamespaceName('A');
        $otherNamespaceName = new NamespaceName('B');

        /* When */
        $result = $namespaceName->isParentOf($otherNamespaceName);

        /* Then */
        $this->assertFalse($result);
    }

    public function test_isParentOf_shouldReturnTrueWhenChild(): void
    {
        /* Given */
        $namespaceName = new NamespaceName('A');
        $otherNamespaceName = new NamespaceName('A\B');

        /* When */
        $result = $namespaceName->isParentOf($otherNamespaceName);

        /* Then */
        $this->assertTrue($result);
    }

    public function test_isParentOf_shouldReturnFalseWhenParent(): void
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
