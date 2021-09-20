<?php

namespace HK\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use HK\CoreBundle\Master\MasterController;
use HK\CoreBundle\Entity\MediaFile;
use HK\AdminBundle\FormType\MediaFileType;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Filesystem\Filesystem;
use HK\CoreBundle\Entity\AboutPage;
use HK\AdminBundle\FormType\AboutPageType;
use HK\CoreBundle\Helper\FileHelper;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class MediaFileController extends MasterController
{

    protected $entityClass = AboutPage::class;

    protected $entityTypeClass = AboutPageType::class;

    protected $isIndexCustom = true;

    protected $isAddEditCustom = true;

    protected $icon = 'file';

    protected $isDisplayOrder = false;

    protected $isDisplayPublishedColumn = false;

    protected $hasContent = false;

    private $mediaTemplate = 'admin/mediafile/subin.html.twig';

    private function getExt($fileName)
    {
        $arr = explode('.', $fileName);
        return end($arr);
    }

    public function subin(Request $req, ParameterBagInterface $param): Response
    {
        if (!$this->isPermissionView()) {
            return $this->errorJson($this->trans('lbl.role.not-granted'));
        }
        $this->entityObj = new MediaFile();
        $this->form = $this->createForm(MediaFileType::class, $this->entityObj);
        $this->form->handleRequest($req);
        if ($this->form->isSubmitted() && $this->form->isValid()) {
            try {
                $entity = $this->form->getData();
                $files = $entity->getFile();
                $uploadDir = FileHelper::instance()->getRealPathMedia($param) . '/' . $entity->getPath();
                foreach ($files as $file) {
                    if (strpos(getenv('FILE_ALLOWEDS'), '|' . strtolower($this->getExt($file->getClientOriginalName())) . '|') === false) {
                        return $this->errorJson($this->trans('media-file.file-not-allowed'));
                    }
                    $fileName = $file->getClientOriginalName(); // .'-'.time().'.'.$file->guessExtension();
                    if (file_exists($uploadDir . '/' . $fileName)) {
                        return $this->errorJson($this->trans('media-file.file-existing'));
                    }
                }
                foreach ($files as $file) {
                    $fileName = $file->getClientOriginalName(); // .'-'.time().'.'.$file->guessExtension();
                    $file->move($uploadDir, $fileName);
                }
                return $this->okJson([
                    'message' => $this->trans('media-file.file-upload-success')
                ]);
            } catch (\Exception $ex) {
                return $this->errorJson($this->trans('media-file.file-upload-failed'));
            }
        }

        return $this->render($this->mediaTemplate, [
            'breadcrumbs' => [
                [
                    'class' => '',
                    'name' => $this->controllerText,
                    'url' => 'javascript:void(0)',
                    'active' => 'active'
                ]
            ],
            'form' => $this->form->createView(),
            'data' => $this->dataRender
        ]);
    }

    public function indexMedia(Request $req, ParameterBagInterface $param): Response
    {
        $this->mediaTemplate = 'admin/mediafile/index.html.twig';
        return $this->subin($req, $param);
    }

    private function getAllDirectory($path, $relativeName = '')
    {
        if ($path == NULL || empty($path)) {
            return '';
        }
        $data = [];
        $finder = new Finder();
        $directory = $finder->directories()
            ->in($path)
            ->depth(0);
        foreach ($directory as $dir) {
            $href = $relativeName . '/' . $dir->getRelativePathname();
            $data[] = [
                'href' => $href,
                'text' => $dir->getRelativePathname(),
                'nodes' => $this->getAllDirectory($dir->getRealpath(), $href)
            ];
        }
        return count($data) > 0 ? $data : '';
    }

    public function directory(Request $req, ParameterBagInterface $param)
    {
        if (!$this->isPermissionView()) {
            return $this->errorJson($this->trans('lbl.role.not-granted'));
        }
        $finder = new Finder();
        $data = [];

        $finder->directories()
            ->in(FileHelper::instance()->getRealPathMedia($param))
            ->depth(0);
        foreach ($finder as $dir) {
            $href = $dir->getRelativePathname();
            $data[] = [
                'href' => $href,
                'text' => $dir->getRelativePathname(),
                'nodes' => $this->getAllDirectory($dir->getRealpath(), $href)
            ];
        }
        return $this->okJson($data);
    }

    public function getFiles(Request $req, ParameterBagInterface $param): Response
    {
        if (!$this->isPermissionView()) {
            return $this->errorJson($this->trans('lbl.role.not-granted'));
        }
        $dir = '/' . $req->get('path', '');
        $finder = new Finder();
        $finder->files()
            ->in(FileHelper::instance()->getRealPathMedia($param) . $dir)
            ->name('*' . $req->get('keyword', '') . '*')
            ->sort(function ($a, $b) {
                return ($b->getMTime() - $a->getMTime());
            });
        $data = [];
        foreach ($finder as $file) {
            $arr = explode('\\', $file->getRelativePathname());
            $path = FileHelper::instance()->getMediaPath() . str_replace('//', '/', $dir . '/' . str_replace('\\', '/', $file->getRelativePathname()));
            /*if (strpos('|png|jpg|jpeg|bmp|gif|', '|' . strtolower($this->getExt($file->getRelativePathname())) . '|') === false) {
                switch (strtolower($this->getExt($file->getRelativePathname()))) {
                    case 'pdf':
                        $path = '/assets/admin/img/pdf-icon.png';
                        break;
                    case 'zip':
                        $path = '/assets/admin/img/zip-icon.jpg';
                        break;
                    case 'doc':
                    case 'docx':
                        $path = '/assets/admin/img/word-icon.png';
                        break;
                    case 'xls':
                    case 'xlsx':
                        $path = '/assets/admin/img/excel-icon.png';
                        break;
                    default:
                        $path = '/assets/admin/img/not-show-icon.png';
                }
            }*/
            $data[] = [
                'path' => $path, // $this->getMediaPath() . str_replace('//', '/', $dir . '/' . str_replace('\\', '/', $file->getRelativePathname())),
                'name' => $arr[count($arr) - 1],
                'real' => str_replace('//', '/', $dir . '/' . str_replace('\\', '/', $file->getRelativePathname()))
            ];
        }
        return $this->okJson($data);
    }

    public function fileDelete(Request $req, ParameterBagInterface $param): Response
    {
        if (!$this->isPermissionDelete()) {
            return $this->errorJson($this->trans('lbl.role.not-granted'));
        }
        $fs = new Filesystem();
        $dir_file = $req->get('path', '');
        if (empty($dir_file) || !$fs->exists(FileHelper::instance()->getRealPathMedia($param) . $dir_file)) {
            return $this->errorJson($this->trans('media-file.file-not-found'));
        }
        try {
            $fs->remove(FileHelper::instance()->getRealPathMedia($param) . $dir_file);
            return $this->okJson([
                'message' => $this->trans('media-file.file-delete-success')
            ]);
        } catch (\Exception $ex) {
            return $this->errorJson($this->trans('media-file.file-not-found'));
        }
    }

    public function folderDelete(Request $req, ParameterBagInterface $param): Response
    {
        if (!$this->isPermissionDelete()) {
            return $this->errorJson($this->trans('lbl.role.not-granted'));
        }
        $path = $req->get('path', '');
        $path = empty($path) ? '/' : ('/' . $path . '/');
        $fs = new Filesystem();
        if (!$fs->exists(FileHelper::instance()->getRealPathMedia($param) . $path)) {
            return $this->errorJson($this->trans('media-file.path-not-found'));
        }
        $finder = new Finder();
        $finder->files()->in(FileHelper::instance()->getRealPathMedia($param) . $path);
        if ($finder->count() > 0) {
            return $this->errorJson($this->trans('media-file.path-not-empty'));
        }
        try {
            $fs->remove(FileHelper::instance()->getRealPathMedia($param) . $path);
            return $this->okJson([
                'message' => $this->trans('media-file.folder-delete-success')
            ]);
        } catch (\Exception $ex) {
            return $this->errorJson($this->trans('media-file.path-not-found'));
        }
    }

    public function createDirectory(Request $req, ParameterBagInterface $param)
    {
        if (!$this->isPermissionEdit()) {
            return $this->errorJson($this->trans('lbl.role.not-granted'));
        }
        $path = $req->get('path', '');
        $path = empty($path) ? '/' : ('/' . $path . '/');
        $dir_name = $req->get('dir', '');
        $fs = new Filesystem();
        if (empty($dir_name) || !$fs->exists(FileHelper::instance()->getRealPathMedia($param) . $path)) {
            return $this->errorJson($this->trans('media-file.path-not-found'));
        }
        if ($fs->exists(FileHelper::instance()->getRealPathMedia($param) . $path . $dir_name)) {
            return $this->errorJson($this->trans('media-file.path-name-existing'));
        }
        try {
            $fs->mkdir(FileHelper::instance()->getRealPathMedia($param) . $path . $dir_name);
            return $this->okJson([
                'message' => $this->trans('media-file.directory-create-success')
            ]);
        } catch (\Exception $ex) {
            return $this->errorJson($this->trans('media-file.path-name-existing'));
        }
    }
}
