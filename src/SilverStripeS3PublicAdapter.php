<?php

namespace Madmatt\SilverStripeS3;

use Aws\S3\S3Client,
    League\Flysystem\AwsS3v3\AwsS3Adapter,
    SilverStripe\Assets\Flysystem\PublicAdapter;

class SilverStripeS3PublicAdapter extends AwsS3Adapter implements PublicAdapter {
    use SilverStripeS3AdapterTrait;

    public function __construct() {
        $s3Client = new S3Client([
            'credentials' => [
                'key' => $this->findAwsKey(),
                'secret' => $this->findAwsSecret()
            ],
            'region' => $this->findAwsRegion(),
            'version' => 'latest'
        ]);

        parent::__construct($s3Client, $this->findAwsBucket(), 'public');
    }

    /**
     * Used to get the public URL to a file in an S3 bucket. The standard S3 URL is returned (the file is not proxied
     * further via SilverStripe).
     *
     * @param string $path
     * @return string
     */
    public function getPublicUrl($path) {
        return $this->getClient()->getObjectUrl($this->getBucket(), $this->applyPathPrefix($path));
    }
}