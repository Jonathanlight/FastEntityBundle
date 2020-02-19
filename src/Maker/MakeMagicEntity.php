<?php

namespace Jonathankablan\Bundle\FastEntityBundle\Maker;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Doctrine\Common\Annotations\Annotation;
use Doctrine\Common\Inflector\Inflector;
use Jonathankablan\Bundle\FastEntityBundle\Configuration\SettingManager;
use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\DependencyBuilder;
use Symfony\Bundle\MakerBundle\Doctrine\DoctrineHelper;
use Symfony\Bundle\MakerBundle\Doctrine\EntityRegenerator;
use Symfony\Bundle\MakerBundle\FileManager;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\InputConfiguration;
use Symfony\Bundle\MakerBundle\Maker\AbstractMaker;
use Symfony\Bundle\MakerBundle\MakerInterface;
use Symfony\Bundle\MakerBundle\Str;
use Symfony\Bundle\MakerBundle\Validator;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;

final class MakeMagicEntity extends AbstractMaker
{

    protected $setting;
    private $fileManager;
    private $doctrineHelper;
    private $generator;

    public function __construct(
        FileManager $fileManager,
        DoctrineHelper $doctrineHelper,
        string $projectDirectory,
        Generator $generator = null
    )
    {
        $this->setting = new SettingManager();
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
        $dataConfigYml = $this->setting->readYamlConfig();

        $tables = $dataConfigYml['fast_entity']['tables'];

        foreach ($tables as $table) {
            $className = ucfirst($table['name']);
            $entity_alias = strtolower($table['name'])[0];
            $entity_full_class_name = 'App\Entity\\'.$className;
            $repository_full_class_name = 'App\Repository\\'.$className.'Repository';

            $entityClassNameDetails = $generator->createClassNameDetails(
                $className,
                'Entity\\',
                ''
            );

            $generator->generateClass(
                $entityClassNameDetails->getFullName(),
                'doctrine/Entity.tpl.php',
                [
                    'api_resource' => true,
                    'repository_full_class_name' => $repository_full_class_name,
                    'class_name' => $className
                ]
            );
    
            $doctrineClassNameDetails = $generator->createClassNameDetails(
                $className,
                'Repository\\',
                'Repository'
            );
    
            $generator->generateClass(
                $doctrineClassNameDetails->getFullName(),
                'doctrine/Repository.tpl.php',
                [
                    'entity_full_class_name' => $entity_full_class_name,
                    'with_password_upgrade' => false,
                    'entity_class_name' => $className,
                    'api_resource' => true,
                    'entity_alias' => $entity_alias,
                    'repository_full_class_name' => $repository_full_class_name,
                    'class_name' => $className
                ]
            );
        }

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

    private function regenerateEntities(string $classOrNamespace, bool $overwrite, Generator $generator)
    {
        $regenerator = new EntityRegenerator($this->doctrineHelper, $this->fileManager, $generator, $overwrite);
        $regenerator->regenerateEntities($classOrNamespace);
    }

    private function getPropertyNames(string $class): array
    {
        if (!class_exists($class)) {
            return [];
        }

        $reflClass = new \ReflectionClass($class);

        return array_map(function (\ReflectionProperty $prop) {
            return $prop->getName();
        }, $reflClass->getProperties());
    }

    private function getEntityNamespace(): string
    {
        return $this->doctrineHelper->getEntityNamespace();
    }
}
