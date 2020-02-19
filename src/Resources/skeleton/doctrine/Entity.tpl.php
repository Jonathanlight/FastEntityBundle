<?= "<?php\n" ?>

namespace <?= $namespace ?>;

use Doctrine\ORM\Mapping as ORM;

/**
<?php if ($api_resource): ?> * @ApiResource() <?php endif ?>
* @ORM\Entity(repositoryClass="<?= $repository_full_class_name ?>")
*/
class <?= $class_name."\n" ?>
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    public function getId(): ?int
    {
        return $this->id;
    }
}
