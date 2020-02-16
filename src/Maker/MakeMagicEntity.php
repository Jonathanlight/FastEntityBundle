<?php

namespace Jonathankablan\Bundle\FastEntityBundle\Maker;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Doctrine\Common\Annotations\Annotation;
use Doctrine\Common\Inflector\Inflector;
use Jonathankablan\Bundle\FastEntityBundle\Configuration\SettingManager;
use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\DependencyBuilder;
use Symfony\Bundle\MakerBundle\Doctrine\DoctrineHelper;
use Symfony\Bundle\MakerBundle\FileManager;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\InputConfiguration;
use Symfony\Bundle\MakerBundle\Maker\AbstractMaker;
use Symfony\Bundle\MakerBundle\Str;
use Symfony\Bundle\MakerBundle\Validator;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;

final class MakeMagicEntity extends AbstractMaker
{
    /**
     * @var SettingManager
     */
    protected $setting;

    /**
     * @var FileManager
     */
    private $fileManager;

    /**
     * @var DoctrineHelper
     */
    private $doctrineHelper;

    /**
     * @var Generator
     */
    private $generator;

    /**
     * @param SettingManager $setting
     * @param FileManager $fileManager
     * @param DoctrineHelper $doctrineHelper
     * @param string $projectDirectory
     * @param Generator|null $generator
     */
    public function __construct(
        SettingManager $setting,
        FileManager $fileManager,
        DoctrineHelper $doctrineHelper,
        string $projectDirectory,
        Generator $generator = null
    )
    {
        $this->setting = $setting;
        $this->fileManager = $fileManager;
        $this->doctrineHelper = $doctrineHelper;
        // $projectDirectory is unused, argument kept for BC

        if (null === $generator) {
            @trigger_error(sprintf('Passing a "%s" instance as 4th argument is mandatory since version 1.5.', Generator::class), E_USER_DEPRECATED);
            $this->generator = new Generator($fileManager, 'App\\');
        } else {
            $this->generator = $generator;
        }
    }

    public static function getCommandName(): string
    {
        return 'magic:fast:entity';
    }

    public function configureCommand(Command $command, InputConfiguration $inputConf)
    {
        $command
            ->setDescription('Generate structure entity')
            ->setHelp(file_get_contents(__DIR__.'/../Resources/help/MakeEntity.txt'))
        ;
    }

    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator)
    {
        $entityClassDetails = $generator->createClassNameDetails(
            'Student',
            'Entity\\',
            'Entity'
        );

        $entityDoctrineDetails = $this->doctrineHelper->createDoctrineDetails($entityClassDetails->getFullName());

        $repositoryVars = [];

        if (null !== $entityDoctrineDetails->getRepositoryClass()) {
            $repositoryClassDetails = $generator->createClassNameDetails(
                '\\'.$entityDoctrineDetails->getRepositoryClass(),
                'Repository\\',
                'Repository'
            );

            $repositoryVars = [
                'repository_full_class_name' => $repositoryClassDetails->getFullName(),
                'repository_class_name' => $repositoryClassDetails->getShortName(),
                'repository_var' => lcfirst(Inflector::singularize($repositoryClassDetails->getShortName())),
            ];
        }

        dump($this->setting->readYamlConfig());

        $controllerPath = $generator->generateController(
            $entityClassDetails->getFullName(),
            'doctrine/Entity.tpl.php',
            [
                'route_path' => Str::asRoutePath($entityClassDetails->getRelativeNameWithoutSuffix()),
                'route_name' => Str::asRouteName($entityClassDetails->getRelativeNameWithoutSuffix()),
            ]
        );

        $generator->writeChanges();

        $this->writeSuccessMessage($io);
        $io->text('Generate ...');
    }

    public function configureDependencies(DependencyBuilder $dependencies)
    {
        $dependencies->addClassDependency(
            DoctrineBundle::class,
            'orm-pack'
        );

        $dependencies->addClassDependency(
            Annotation::class,
            'doctrine/annotations'
        );
    }
}
