<?php
declare(strict_types=1);
namespace Antarian\Tests\Unit\Model;

use Antarian\Stantie\Model\Category;
use PHPUnit\Framework\TestCase;

class CategoryTest extends TestCase
{
    public function testCategoryWithCorrectData(): void
    {
        $category = new Category(
            slug: '',
            title: '',
            filePath: '',
        );

        $this->assertInstanceOf(Category::class, $category);
    }
}