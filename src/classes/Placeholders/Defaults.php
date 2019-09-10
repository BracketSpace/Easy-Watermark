<?php
/**
 * Default placeholders loader
 *
 * @package easy-watermark
 */

namespace EasyWatermark\Placeholders;

use EasyWatermark\Placeholders\Attachment;
use EasyWatermark\Placeholders\Author;
use EasyWatermark\Placeholders\Blog;
use EasyWatermark\Placeholders\DateTime;
use EasyWatermark\Placeholders\User;
use EasyWatermark\Traits\Hookable;

/**
 * Defaults class
 */
class Defaults {

	use Hookable;

	/**
	 * Constructor
	 *
	 * @return void
	 */
	public function __construct() {
		$this->hook();
	}

	/**
	 * Returns placeholder slug
	 *
	 * @action easy-watermark/placeholders/load
	 *
	 * @param  Resolver $resolver Placeholders resolver instance.
	 * @return void
	 */
	public function load_default_placeholders( $resolver ) {

		$resolver->add_placeholder( new User\UserLogin() );
		$resolver->add_placeholder( new User\UserDisplayName() );
		$resolver->add_placeholder( new User\UserFirstName() );
		$resolver->add_placeholder( new User\UserLastName() );
		$resolver->add_placeholder( new User\UserNicename() );
		$resolver->add_placeholder( new User\UserEmail() );
		$resolver->add_placeholder( new User\UserUrl() );
		$resolver->add_placeholder( new User\UserRole() );
		$resolver->add_placeholder( new User\UserID() );

		$resolver->add_placeholder( new Author\AuthorLogin() );
		$resolver->add_placeholder( new Author\AuthorDisplayName() );
		$resolver->add_placeholder( new Author\AuthorFirstName() );
		$resolver->add_placeholder( new Author\AuthorLastName() );
		$resolver->add_placeholder( new Author\AuthorNicename() );
		$resolver->add_placeholder( new Author\AuthorEmail() );
		$resolver->add_placeholder( new Author\AuthorUrl() );
		$resolver->add_placeholder( new Author\AuthorRole() );
		$resolver->add_placeholder( new Author\AuthorID() );

		$resolver->add_placeholder( new Blog\BlogName() );
		$resolver->add_placeholder( new Blog\BlogUrl() );
		$resolver->add_placeholder( new Blog\AdminEmail() );

		$resolver->add_placeholder( new Attachment\AttachmentID() );
		$resolver->add_placeholder( new Attachment\AttachmentDirectUrl() );
		$resolver->add_placeholder( new Attachment\AttachmentPage() );
		$resolver->add_placeholder( new Attachment\AttachmentTitle() );
		$resolver->add_placeholder( new Attachment\AttachmentMimeType() );
		$resolver->add_placeholder( new Attachment\AttachmentWidth() );
		$resolver->add_placeholder( new Attachment\AttachmentHeight() );
		$resolver->add_placeholder( new Attachment\AttachmentSize() );
		$resolver->add_placeholder( new Attachment\AttachmentUploadDate() );

		$resolver->add_placeholder( new DateTime\Date() );
		$resolver->add_placeholder( new DateTime\Time() );

	}

}
