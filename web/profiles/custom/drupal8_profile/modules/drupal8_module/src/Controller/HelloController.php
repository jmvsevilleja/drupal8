<?php
/**
 * @file
 * Contains \Drupal\hello_world\Controller\HelloController.
 */
namespace Drupal\drupal8_module\Controller;


class HelloController {
  /**
   * The file system.
   *
   * @var \Drupal\Core\File\FileSystemInterface
   */
  protected $fileSystem;

    /**
   * Entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  public function __construct(EntityTypeManagerInterface $entityTypeManager, FileSystemInterface $fileSystem) {
		$this->fileSystem = $fileSystem;
    $this->entityTypeManager = $entityTypeManager;
	}

  public function content() {

		$image_path = '/tmp/' . 'test.jpg';
		$values['field_background'] = [
			'target_id' => $this->createFileEntity($image_path),
		];

		print_r($values);
    return array(
      '#type' => 'markup',
      '#markup' => t('Hello, World!'),
    );
	}



  /**
   * Creates a file entity based on an image path.
   *
   * @param string $path
   *   Image path.
   *
   * @return int
   *   File ID.
   */
  protected function createFileEntity($path) {
		$filename = basename($path);
    try {
      $uri = $this->fileSystem->copy($path, 'public://' . $filename, FileSystemInterface::EXISTS_REPLACE);
    }
    catch (FileException $e) {
      $uri = FALSE;
		}

    $file = $this->entityTypeManager->getStorage('file')->create([
      'uri' => $uri,
      'status' => 1,
    ]);
    $file->save();
    $this->storeCreatedContentUuids([$file->uuid() => 'file']);
    return $file->id();
  }

}