CREATE TABLE `seen` (
 `person_id` int(11) NOT NULL default '0',
 `message_id` int(11) NOT NULL default '0',
 `seen` int(11) NOT NULL default '0',
 `seen_when` timestamp NULL default '0000-00-00 00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Database: `demo`
--

-- --------------------------------------------------------

--
-- Table structure for table `envelopes`
--

CREATE TABLE `envelopes` (
 `envelope_id` int(11) NOT NULL default '0',
 `to_person` int(11) NOT NULL default '0',
 `from_person` int(11) NOT NULL default '0',
 `message_id` int(11) NOT NULL default '0',
 `when_read` timestamp NULL default '0000-00-00 00:00:00',
 `when_sent` timestamp NULL default '0000-00-00 00:00:00',
 PRIMARY KEY  (`envelope_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `mmmessages`
-- 

CREATE TABLE `mmmessages` (
  `message_id` int(11) NOT NULL default '0',
  `thread_id` int(11) NOT NULL default '0',
  `created` timestamp NULL default '0000-00-00 00:00:00',
  `is_todo` int(11) NOT NULL default '0',
  `is_done` int(11) NOT NULL default '0',
  `priority` int(5) NOT NULL default '0',
  `content` text NOT NULL,
  PRIMARY KEY  (`message_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `threads`
--

CREATE TABLE IF NOT EXISTS `threads` (
  `thread_id` int(11) NOT NULL default '0',
  `thread_name` varchar(255) NOT NULL default '',
  `patient_id` int(10) NOT NULL,
  `is_todo` tinyint(4) NOT NULL default '0',
  `is_done` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`thread_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
