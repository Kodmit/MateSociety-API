<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\CreateMediaObjectAction;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity
 * @ApiResource(
 *     iri="http://schema.org/MediaObject",
 *     normalizationContext={
 *         "groups"={"media_object_read"},
 *     },
 *     collectionOperations={
 *         "post"={
 *             "controller"=CreateMediaObjectAction::class,
 *             "defaults"={
 *                 "_api_receive"=false,
 *             },
 *             "access_control"="is_granted('ROLE_USER')",
 *             "validation_groups"={"Default", "media_object_create"},
 *             "swagger_context"={
 *                 "consumes"={
 *                     "multipart/form-data",
 *                 },
 *                 "parameters"={
 *                     {
 *                         "in"="formData",
 *                         "name"="file",
 *                         "type"="file",
 *                         "description"="The file to upload",
 *                     },
 *                 },
 *             },
 *         },
 *         "get",
 *     },
 *     itemOperations={
 *         "get",
 *     },
 * )
 * @Vich\Uploadable
 */
class MediaObject
{
    /**
     * @var int|null
     *
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     * @ORM\Id
     */
    protected $id;

    /**
     * @var string|null
     *
     * @ApiProperty(iri="http://schema.org/contentUrl")
     * @Groups({"media_object_read"})
     */
    public $contentUrl;

    /**
     * @var File|null
     * @Assert\File(
     *     maxSize = "5M",
     *     mimeTypes = {"image/jpeg", "image/png"},
     *     mimeTypesMessage = "Please upload a valid image"
     * )
     * @Assert\NotNull(groups={"media_object_create"})
     * @Vich\UploadableField(mapping="media_object", fileNameProperty="filePath")
     */
    public $file;

    /**
     * @var string|null
     *
     * @ORM\Column(nullable=true)
     * @Groups({"read_user"})
     */
    public $filePath;


    public function getId(): ?int
    {
        return $this->id;
    }
}