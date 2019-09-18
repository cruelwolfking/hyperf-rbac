<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Files;
use App\Model\Sites;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;

class UploadController
{
    /**
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function upload(RequestInterface $request, ResponseInterface $response)
    {
        $url = $request->url();

        if(Sites::where('site_url',$url)->exists()){

            if ($request->hasFile('file')) {

                if ($request->file('file')->isValid()) {

                    // 由于 Swoole 上传文件的 tmp_name 并没有保持文件原名，所以这个方法已重写为获取原文件名的后缀名
                    $extension = $request->file('file')->getExtension();

                    $file = $request->file('file');

                    $filename = time().'.'.$extension;
                    $path = '/uploads/'.$filename;
                    $file->moveTo($path);

                    // 通过 isMoved(): bool 方法判断方法是否已移动
                    if ($file->isMoved()) {
                        $data = [
                            'original_name' => $file->getFilename(),
                            'size' => $file->getSize(),
                            'ext'=>$extension,
                            'path'=>$path,
                            'filename'=>$filename
                        ];
                        Files::create($data);

                        return $response->json(['code'=>200,'msg'=>'文件上传成功！','data'=>$path]);
                    }
                    return $response->json(['code'=>500,'msg'=>'文件保存失败！']);
                }
                return $response->json(['code'=>500,'msg'=>'文件上传失败！']);
            }

            return $response->json(['code'=>405,'msg'=>'请选择需要上传的文件！']);

        }

        return $response->json(['code'=>403,'msg'=>'请求地址不合法！']);
    }
}
