<?php defined('SYSPATH') or die('No direct script access.');

class Model_AclTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Test data
	 */
	const GUEST 	= '33101879-79e9-4688-d5ec-208d87797691';
	const MEMBER 	= 'b35ae76f-3e95-4b0f-f83b-c4718f7de5a6';
	const ADMIN 	= 'e19f6491-4b35-4153-93d1-0b955110260f';
	
	const ARTICLE 	= '54ae7e16-ba29-4aa7-fbc1-6c178ede5910';
	const COMMENT 	= '97034356-c45a-4857-cf2a-52626550999f';
	const GALLERY 	= 'e866eff9-7e39-4594-f79d-0b3cf5d169a1';
	
	const ARTICLE_VIEW = 'ARTICLE_VIEW';
	const ARTICLE_ADD = 'ARTICLE_ADD';
	const ARTICLE_EDIT = 'ARTICLE_EDIT';
	const ARTICLE_DELETE = 'ARTICLE_DELETE';
	
	const COMMENT_ADD = 'COMMENT_ADD';
	const COMMENT_EDIT = 'COMMENT_EDIT';
	const COMMENT_DELETE = 'COMMENT_DELETE';
	
	const GALLERY_VIEW = 'GALLERY_VIEW';
	const GALLERY_ADD = 'GALLERY_ADD';
	const GALLERY_EDIT = 'GALLERY_EDIT';
	const GALLERY_DELETE = 'GALLERY_DELETE';
	
	const ALLOW = 1;
	const DENY = 0;
	
	protected $_testRoleData = array(
		self::GUEST => array(
			'role_id' => self::GUEST,
			'role_name' => 'guest',
			'role_description' => 'Guest users / not logged in'
		),
		self::MEMBER => array(
			'role_id' => self::MEMBER,
			'role_name' =>'member',
			'role_description' => 'Logged in user'
		),
		self::ADMIN => array(
			'role_id' => self::ADMIN,
			'role_name' => 'admin',
			'role_description' => 'Admin user'
		)
	);
	
	protected $_testResourceData = array(
		self::ARTICLE => array(
			'resource_id' => self::ARTICLE,
			'resource_name' => 'article',
			'resource_description' => 'Article resource'
		),
		self::COMMENT => array(
			'resource_id' => self::COMMENT,
			'resource_name' =>'comment',
			'resource_description' => 'Comments resource'
		),
		self::GALLERY => array(
			'resource_id' => self::GALLERY,
			'resource_name' => 'gallery',
			'resource_description' => 'Gallery resource'
		)
	);
	
	protected $_testPrivilegeData = array(
		// viewing article privileges
		array(
			'role_id'			=> self::GUEST,
			'resource_id'		=> self::ARTICLE,
			'privilege_name'	=> self::ARTICLE_VIEW,
			'allow'				=> self::ALLOW,
			'privilege_description' => 'allow guest to view article'
		),
		array(
			'role_id'			=> self::MEMBER,
			'resource_id'		=> self::ARTICLE,
			'privilege_name'	=> self::ARTICLE_VIEW,
			'allow'				=> self::ALLOW,
			'privilege_description' => 'allow member to view article'
		),
		// allow all privileges to admin for all resources
		array(
			'role_id'			=> self::ADMIN,
			'resource_id'		=> 0,
			'privilege_name'	=> '',
			'allow'				=> self::ALLOW,
			'privilege_description' => 'allow all privileges to admin for article'
		),
		// we don't need to deny guest for ARTICLE_ADD, its automatic
		// not listed means denied
		// adding article privileges
		array(
			'role_id'			=> self::MEMBER,
			'resource_id'		=> self::ARTICLE,
			'privilege_name'	=> self::ARTICLE_ADD,
			'allow'				=> self::ALLOW,
			'privilege_description' => 'allow member to add article'
		),
		// editting article privileges
		array(
			'role_id'			=> self::MEMBER,
			'resource_id'		=> self::ARTICLE,
			'privilege_name'	=> self::ARTICLE_EDIT,
			'allow'				=> self::ALLOW,
			'privilege_description' => 'allow member to edit article'
		),
		// comment privileges for guests, member and admin
		// member has add privilege for comment (add only and no more)
		array(
			'role_id'			=> self::MEMBER,
			'resource_id'		=> self::COMMENT,
			'privilege_name'	=> self::COMMENT_ADD,
			'allow'				=> self::ALLOW,
			'privilege_description' => 'allow member to add comment'
		),
		// viewing gallery privileges
		array(
			'role_id'			=> self::MEMBER,
			'resource_id'		=> self::GALLERY,
			'privilege_name'	=> self::GALLERY_VIEW,
			'allow'				=> self::ALLOW,
			'privilege_description' => 'allow member to view gallery'
		),
		// adding gallery privileges
		array(
			'role_id'			=> self::MEMBER,
			'resource_id'		=> self::GALLERY,
			'privilege_name'	=> self::GALLERY_ADD,
			'allow'				=> self::ALLOW,
			'privilege_description' => 'allow member to add gallery'
		),
		// editting gallery privileges 
		array(
			'role_id'			=> self::MEMBER,
			'resource_id'		=> self::GALLERY,
			'privilege_name'	=> self::GALLERY_EDIT,
			'allow'				=> self::ALLOW,
			'privilege_description' => 'allow member to edit gallery'
		),
		// try unknown role
		array(
			'role_id'			=> '0000001',
			'resource_id'		=> '0000002',
			'privilege_name'	=> 'ABCDEFG',
			'allow'				=> self::ALLOW,
			'privilege_description' => 'allow member to edit gallery'
		)
	);	
	
	public function testObject()
	{
		$acl = new Model_Acl;
		$this->assertType('Model_Acl', $acl);
		
		return $acl;
	}
	
	/**
	 * @depends testObject
	 * @param Model_Acl $acl
	 */
	public function testInit(Model_Acl $acl)
	{
		$roleDataSource = $this->getMock(
			'Model_Mapper_Role', array('getAll')
		);
		$roleDataSource->expects($this->any())
			->method('getAll')
			->will($this->returnValue($this->_testRoleData));
				   
		$resourceDataSource = $this->getMock(
			'Model_Mapper_Resource', array('getAll')
		);
		$resourceDataSource->expects($this->any())
			->method('getAll')
			->will($this->returnValue($this->_testResourceData)
		);
			
		$privilegeDataSource = $this->getMock(
			'Model_Mapper_Privilege', array('getAll')
		);
		$privilegeDataSource->expects($this->any())
			->method('getAll')
			->will($this->returnValue($this->_testPrivilegeData)
		);
			
		Model_Acl::setRoleDataSource($roleDataSource);
		Model_Acl::setResourceDataSource($resourceDataSource);
		Model_Acl::setPrivilegeDataSource($privilegeDataSource);
		
		$this->assertType('Model_Acl', $acl->init());
		
		return $acl;
	}
	
	/**
	 * @depends testInit
	 * @param Model_Acl $acl
	 */
	public function testRulesArticles(Model_Acl $acl)
	{
		// test guest for viewing atricles
		// guest can only view articles
		$this->assertTrue($acl->isAllowed('guest', 'article', self::ARTICLE_VIEW));
		// guest can't add article
		$this->assertFalse($acl->isAllowed('guest', 'article', self::ARTICLE_ADD));
		// guest can't edit article
		$this->assertFalse($acl->isAllowed('guest', 'article', self::ARTICLE_EDIT));
		// guest can't delete article
		$this->assertFalse($acl->isAllowed('guest', 'article', self::ARTICLE_DELETE));
		
		// test member for article privileges
		// member can view articles
		$this->assertTrue($acl->isAllowed('member', 'article', self::ARTICLE_VIEW));
		// member can add article
		$this->assertTrue($acl->isAllowed('member', 'article', self::ARTICLE_ADD));
		// member can edit article
		$this->assertTrue($acl->isAllowed('member', 'article', self::ARTICLE_EDIT));
		// member can't delete article
		$this->assertFalse($acl->isAllowed('member', 'article', self::ARTICLE_DELETE));
		
		// test admin for article privileges
		// admin can view articles
		$this->assertTrue($acl->isAllowed('admin', 'article', self::ARTICLE_VIEW));
		// admin can add article
		$this->assertTrue($acl->isAllowed('admin', 'article', self::ARTICLE_ADD));
		// admin can edit article
		$this->assertTrue($acl->isAllowed('admin', 'article', self::ARTICLE_EDIT));
		// admin can delete article
		$this->assertTrue($acl->isAllowed('admin', 'article', self::ARTICLE_DELETE));
	}
	
	/**
	 * @depends testInit
	 * @param Model_Acl
	 */
	public function testRulesComment(Model_Acl $acl)
	{
		// test guest for comment privileges
		// has no privilege for add, edit and delete
		// view? well comment is included in article anyway
		$this->assertFalse($acl->isAllowed('guest', 'comment', self::COMMENT_ADD));
		// guest can't edit comment
		$this->assertFalse($acl->isAllowed('guest', 'comment', self::COMMENT_EDIT));
		// guest can't delete comment
		$this->assertFalse($acl->isAllowed('guest', 'comment', self::COMMENT_DELETE));
		
		// test member for comment privileges
		// member can add comment
		$this->assertTrue($acl->isAllowed('member', 'comment', self::COMMENT_ADD));
		// member can't edit comment
		$this->assertFalse($acl->isAllowed('member', 'comment', self::COMMENT_EDIT));
		// member can't delete comment
		$this->assertFalse($acl->isAllowed('member', 'comment', self::COMMENT_DELETE));
		
		// test admin for comment privileges
		// admin can add comment
		$this->assertTrue($acl->isAllowed('admin', 'comment', self::COMMENT_ADD));
		// admin can edit comment
		$this->assertTrue($acl->isAllowed('admin', 'comment', self::COMMENT_EDIT));
		// admin can delete comment
		$this->assertTrue($acl->isAllowed('admin', 'comment', self::COMMENT_DELETE));
	}
	
	/**
	 * @depends testInit
	 * @param Model_Acl $acl
	 */
	public function testRulesGallery(Model_Acl $acl)
	{
		// test guest privileges for gallery
		// guest can't view gallery
		$this->assertFalse($acl->isAllowed('guest', 'gallery', self::GALLERY_VIEW));
		// guest can't add gallery
		$this->assertFalse($acl->isAllowed('guest', 'gallery', self::GALLERY_ADD));
		// guest can't edit gallery
		$this->assertFalse($acl->isAllowed('guest', 'gallery', self::GALLERY_EDIT));
		// guest can't delete gallery
		$this->assertFalse($acl->isAllowed('guest', 'gallery', self::GALLERY_DELETE));
		
		// test member privileges for gallery
		// member can view gallery
		$this->assertTrue($acl->isAllowed('member', 'gallery', self::GALLERY_VIEW));
		// member can add gallery
		$this->assertTrue($acl->isAllowed('member', 'gallery', self::GALLERY_ADD));
		// member can edit gallery
		$this->assertTrue($acl->isAllowed('member', 'gallery', self::GALLERY_EDIT));
		// member can't delete gallery
		$this->assertFalse($acl->isAllowed('member', 'gallery', self::GALLERY_DELETE));
		
		// test admin privileges for gallery
		// admin can view gallery
		$this->assertTrue($acl->isAllowed('admin', 'gallery', self::GALLERY_VIEW));
		// admin can add gallery
		$this->assertTrue($acl->isAllowed('admin', 'gallery', self::GALLERY_ADD));
		// admin can edit gallery
		$this->assertTrue($acl->isAllowed('admin', 'gallery', self::GALLERY_EDIT));
		// admin can delete gallery
		$this->assertTrue($acl->isAllowed('admin', 'gallery', self::GALLERY_DELETE));
	}
	
	/**
	 * @depends testInit
	 * @param Model_Acl $acl
	 */
	public function testNonExisting(Model_Acl $acl)
	{
		$this->assertFalse($acl->isAllowed('unknown_user'));
		
		// test unknown resource
		$this->assertFalse($acl->isAllowed('guest', 'unknown_resource'));
		
		// test unknown privilege
		$this->assertFalse($acl->isAllowed('member', 'article', 'unknown_privilege'));
	}
}