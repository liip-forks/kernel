<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\Spryker\Zed\Kernel\IdeAutoCompletion;

use Spryker\Zed\Kernel\IdeAutoCompletion\MethodTagBuilder\ConstructableMethodTagBuilder;

/**
 * @group Kernel
 * @group MethodTagBuilder
 */
class ConstructableMethodTagBuilderTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testBuildMethodTagsShouldReturnVendorMethodTagIfProjectDoesNotOverrideIt()
    {
        $options = [
            ConstructableMethodTagBuilder::OPTION_KEY_APPLICATION => 'Application',
            ConstructableMethodTagBuilder::OPTION_KEY_PATH_PATTERN => 'Communication/',
            ConstructableMethodTagBuilder::OPTION_KEY_PROJECT_PATH_PATTERN => __DIR__ . '/Fixtures/src/',
            ConstructableMethodTagBuilder::OPTION_KEY_VENDOR_PATH_PATTERN => __DIR__ . '/Fixtures/vendor/*/*/src/',
        ];

        require_once __DIR__ . '/Fixtures/vendor/vendor/package/src/VendorNamespace/Application/Bundle/Communication/Plugin/Foo.php';
        require_once __DIR__ . '/Fixtures/vendor/vendor/package/src/VendorNamespace/Application/Bundle/Communication/Plugin/Bar.php';

        $methodTagBuilder = new ConstructableMethodTagBuilder($options);
        $methodTags = $methodTagBuilder->buildMethodTags('Bundle');

        $expectedMethodTag =
            ' * @method \VendorNamespace\Application\Bundle\Communication\Plugin\Foo createPluginFoo()';
        $this->assertContains($expectedMethodTag, $methodTags);

        $expectedMethodTag =
            ' * @method \VendorNamespace\Application\Bundle\Communication\Plugin\Bar createPluginBar()';

        $this->assertContains($expectedMethodTag, $methodTags);
    }

    /**
     * @return void
     */
    public function testBuildMethodTagsShouldReturnProjectMethodTagIfProjectOverrideIt()
    {
        $options = [
            ConstructableMethodTagBuilder::OPTION_KEY_APPLICATION => 'Application',
            ConstructableMethodTagBuilder::OPTION_KEY_PATH_PATTERN => 'Communication/',
            ConstructableMethodTagBuilder::OPTION_KEY_PROJECT_PATH_PATTERN => __DIR__ . '/Fixtures/src/',
            ConstructableMethodTagBuilder::OPTION_KEY_VENDOR_PATH_PATTERN => __DIR__ . '/Fixtures/vendor/*/*/src/',
        ];

        require_once __DIR__ . '/Fixtures/vendor/vendor/package/src/VendorNamespace/Application/Bundle/Communication/Plugin/Baz.php';
        require_once __DIR__ . '/Fixtures/src/ProjectNamespace/Application/Bundle/Communication/Plugin/Baz.php';

        $methodTagBuilder = new ConstructableMethodTagBuilder($options);
        $methodTags = $methodTagBuilder->buildMethodTags('Bundle');

        $expectedMethodTag =
            ' * @method \ProjectNamespace\Application\Bundle\Communication\Plugin\Baz createPluginBaz()';
        $this->assertContains($expectedMethodTag, $methodTags);
    }

    /**
     * @return void
     */
    public function testMethodTagsForYvesShouldContainCommunicationIfClassNamePartLevelIsSet()
    {
        $options = [
            ConstructableMethodTagBuilder::OPTION_KEY_PATH_PATTERN => '',
            ConstructableMethodTagBuilder::OPTION_KEY_APPLICATION => 'Application',
            ConstructableMethodTagBuilder::OPTION_KEY_PROJECT_PATH_PATTERN => __DIR__ . '/Fixtures/src/',
            ConstructableMethodTagBuilder::OPTION_KEY_CLASS_NAME_PART_LEVEL => 3,
        ];

        require_once __DIR__ . '/Fixtures/src/ProjectNamespace/Application/Bundle/Communication/Form/FooForm.php';

        $methodTagBuilder = new ConstructableMethodTagBuilder($options);
        $methodTags = $methodTagBuilder->buildMethodTags('Bundle');

        $expectedMethodTag =
            ' * @method \ProjectNamespace\Application\Bundle\Communication\Form\FooForm createCommunicationFormFooForm()';
        $this->assertContains($expectedMethodTag, $methodTags);
    }

    /**
     * @return void
     */
    public function testBuildMethodTagsShouldReturnMethodNameWithParamsIfClassConstructorHasParams()
    {
        $options = [
            ConstructableMethodTagBuilder::OPTION_KEY_APPLICATION => 'Application',
            ConstructableMethodTagBuilder::OPTION_KEY_PATH_PATTERN => 'Persistence/',
            ConstructableMethodTagBuilder::OPTION_KEY_PROJECT_PATH_PATTERN => __DIR__ . '/Fixtures/src/',
            ConstructableMethodTagBuilder::OPTION_KEY_VENDOR_PATH_PATTERN => __DIR__ . '/Fixtures/vendor/*/*/src/',
        ];

        require_once __DIR__ . '/Fixtures/src/ProjectNamespace/Application/Bundle/Persistence/BundleQueryContainer.php';

        $methodTagBuilder = new ConstructableMethodTagBuilder($options);
        $methodTags = $methodTagBuilder->buildMethodTags('Bundle');
        $expectedMethodTag =
            ' * @method \ProjectNamespace\Application\Bundle\Persistence\BundleQueryContainer createBundleQueryContainer(array $foo, $bar = true, $baz = null, $baz2 = \'abc\', $baz3 = \'\\\\\')';
        $this->assertContains($expectedMethodTag, $methodTags);
    }

    /**
     * @return void
     */
    public function testBuildMethodTagsShouldNotReturnMethodTagIfNotInstantiable()
    {
        $options = [
            ConstructableMethodTagBuilder::OPTION_KEY_APPLICATION => 'Application',
            ConstructableMethodTagBuilder::OPTION_KEY_PATH_PATTERN => 'Communication/',
            ConstructableMethodTagBuilder::OPTION_KEY_PROJECT_PATH_PATTERN => __DIR__ . '/Fixtures/src/',
            ConstructableMethodTagBuilder::OPTION_KEY_VENDOR_PATH_PATTERN => __DIR__ . '/Fixtures/vendor/*/*/src/',
        ];

        require_once __DIR__ . '/Fixtures/src/ProjectNamespace/Application/Bundle/Communication/Plugin/Baz.php';
        require_once __DIR__ . '/Fixtures/src/ProjectNamespace/Application/Bundle/Communication/Plugin/AbstractFoo.php';

        $methodTagBuilder = new ConstructableMethodTagBuilder($options);
        $methodTags = $methodTagBuilder->buildMethodTags('Bundle');

        $expectedMethodTag =
            ' * @method \ProjectNamespace\Application\Bundle\Communication\Plugin\Baz createPluginBaz()';
        $this->assertContains($expectedMethodTag, $methodTags);

        $notAllowedMethodTag =
            ' * @method \ProjectNamespace\Application\Bundle\Communication\Plugin\AbstractFoo createPluginAbstractFoo()';
        $this->assertNotContains($notAllowedMethodTag, $methodTags);
    }

}
