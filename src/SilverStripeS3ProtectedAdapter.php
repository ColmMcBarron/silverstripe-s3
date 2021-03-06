<?php

namespace Madmatt\SilverStripeS3;

use Aws\S3\S3Client,
    League\Flysystem\AwsS3v3\AwsS3Adapter,
    SilverStripe\Assets\Flysystem\ProtectedAdapter,
    SilverStripe\Control\Controller;

/**
 * An adapter that allows the use of AWS S3 to store and transmit assets rather than storing them locally.
 *
 * @package Madmatt\SilverStripeS3
 */
class SilverStripeS3ProtectedAdapter extends AwsS3Adapter implements ProtectedAdapter {
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

		parent::__construct($s3Client, $this->findAwsBucket(), 'protected');
	}

    /**
     * Used by SilverStripe to get the protected URL for this file. This utilises the default ProtectedFileController
     * class to read the file from AWS (rather than linking to AWS directly).
     *
     * @param string $path
     * @return string
     */
	public function getProtectedUrl($path) {
        // Utilise the default SilverStripe\Assets\Storage\ProtectedFileController to shield access
        return Controller::join_links(ASSETS_DIR, $path);
    }
}


