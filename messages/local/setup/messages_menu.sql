
-- 
-- Dumping data for table `menu`
-- 

INSERT INTO `menu` (`menu_id`, `site_section`, `parent`, `dynamic_key`, `section`, `display_order`, `title`, `action`, `prefix`) VALUES 
(283, 'messages', 1, '', 'children', 10, 'Menu', '', 'main'),
(284, 'messages', 283, '', 'children', 100, 'Inbox', 'Messages/Inbox', 'main'),
(285, 'messages', 283, '', 'children', 200, 'Sent', 'Messages/Sent', 'main'),
(286, 'messages', 283, '', 'children', 200, 'Allbox', 'Messages/Allbox', 'main'),
(287, 'messages', 283, '', 'children', 200, 'Compose', 'Messages/New', 'main');
