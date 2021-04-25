SET @sName = 'vnd_test';


-- PAGE: module administration
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('vnd_test_custom', '_vnd_test_page_title_sys_manage_custom', '_bx_accnt_page_title_custom', @sName, 5, 192, 1, 'custom', 'page.php?i=custom', '', '', '', 0, 1, 0, '', '');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('vnd_test_custom', 1, @sName, '_vnd_test_page_block_title_system_manage_custom', '_vnd_test_page_block_title_custom', 11, 192, 'service', 'a:2:{s:6:"module";s:8:"vnd_test";s:6:"method";s:6:"custom";}', 0, 1, 0);


INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('vnd_test_password', '_vnd_test_password', '_vnd_test_password', @sName, 5, 2147483647, 1, 'invite', 'page.php?i=invite', '', '', '', 0, 1, 0, '', '');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('vnd_test_password', 1, @sName, '_vnd_test_invite', '_vnd_test_invite', 10, 2147483647, 'service', 'a:2:{s:6:"module";s:8:"vnd_test";s:6:"method";s:6:"custom";}', 0, 1, 0);

