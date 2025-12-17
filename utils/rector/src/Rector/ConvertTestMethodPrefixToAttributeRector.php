<?php

declare(strict_types=1);

namespace Utils\Rector\Rector;

use Illuminate\Support\Str;
use PhpParser\Node;
use PhpParser\Node\Attribute;
use PhpParser\Node\AttributeGroup;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Analyser\MutatingScope;
use PHPUnit\Framework\Attributes\Test;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\Rector\AbstractRector;
use Tests\TestCase;

final class ConvertTestMethodPrefixToAttributeRector extends AbstractRector
{
    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [ClassMethod::class];
    }

    /**
     * @param  ClassMethod  $node
     */
    public function refactor(Node $node): ?Node
    {
        /** @var Class_ $originalNode */
        $originalNode = $node->getAttribute(AttributeKey::ORIGINAL_NODE);

        if (! Str::of($originalNode->name->toString())->startsWith('test')) {
            return null;
        }

        /** @var MutatingScope $scope */
        $scope = $node->getAttribute(AttributeKey::SCOPE);
        $parents = collect($scope->getClassReflection()->getParents());

        $isInTestCase = $parents->some(fn ($parent) => $parent->getName() === TestCase::class);

        if (! $isInTestCase) {
            return null;
        }

        $this->addAttribute($node, Test::class);

        return $node;
    }

    private function addAttribute(ClassMethod $method, string $attributeClassName): void
    {
        $attribute = new Attribute(
            new FullyQualified($attributeClassName),
            []
        );

        $attributeGroup = new AttributeGroup([$attribute]);
        $method->attrGroups[] = $attributeGroup;

        $methodName = Str::of($method->name->toString())->after('test')->lcfirst()->toString();
        $method->name = new Identifier($methodName);
    }
}
