-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jan 30, 2026 at 09:36 AM
-- Server version: 11.8.3-MariaDB-log
-- PHP Version: 7.2.34
-- CORRECTED: School Student and College Student questions separated

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u433951778_carergsurvey`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`id`, `username`, `password`, `created_at`) VALUES
(1, 'admin', '$2y$10$CpU.N222bI6DBUf4QmuSP.suegRiDJdT3Ghn32PDgRfjP85zlPKg.', '2026-01-29 07:54:52');

-- --------------------------------------------------------

--
-- Table structure for table `answers`
--

CREATE TABLE `answers` (
  `id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `answer_text` varchar(255) NOT NULL,
  `order_no` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `answers`
--

INSERT INTO `answers` (`id`, `question_id`, `answer_text`, `order_no`) VALUES
(136, 38, '1', 1),
(137, 38, '2', 2),
(138, 38, '3', 3),
(139, 38, '4', 4),
(140, 38, '5', 5),
(141, 39, '1', 1),
(142, 39, '2', 2),
(143, 39, '3', 3),
(144, 39, '4', 4),
(145, 39, '5', 5),
(146, 40, '1', 1),
(147, 40, '2', 2),
(148, 40, '3', 3),
(149, 40, '4', 4),
(150, 40, '5', 5),
(155, 42, '1', 1),
(156, 42, '2', 2),
(157, 42, '3', 3),
(158, 42, '4', 4),
(159, 42, '5', 5),
(160, 43, '1', 1),
(161, 43, '2', 2),
(162, 43, '3', 3),
(163, 43, '4', 4),
(164, 43, '5', 5),
(170, 45, '1', 1),
(171, 45, '2', 2),
(172, 45, '3', 3),
(173, 45, '4', 4),
(174, 45, '5', 5),
(175, 46, '1', 1),
(176, 46, '2', 2),
(177, 46, '3', 3),
(178, 46, '4', 4),
(179, 46, '5', 5),
(180, 47, '1', 1),
(181, 47, '2', 2),
(182, 47, '3', 3),
(183, 47, '4', 4),
(184, 47, '5', 5),
(185, 48, '1', 1),
(186, 48, '2', 2),
(187, 48, '3', 3),
(188, 48, '4', 4),
(189, 48, '5', 5),
(190, 49, '1', 1),
(191, 49, '2', 2),
(192, 49, '3', 3),
(193, 49, '4', 4),
(194, 49, '5', 5),
(195, 50, '1', 1),
(196, 50, '2', 2),
(197, 50, '3', 3),
(198, 50, '4', 4),
(199, 50, '5', 5),
(200, 51, '1', 1),
(201, 51, '2', 2),
(202, 51, '3', 3),
(203, 51, '4', 4),
(204, 51, '5', 5),
(205, 52, '1', 1),
(206, 52, '2', 2),
(207, 52, '3', 3),
(208, 52, '4', 4),
(209, 52, '5', 5),
(210, 53, '1', 1),
(211, 53, '2', 2),
(212, 53, '3', 3),
(213, 53, '4', 4),
(214, 53, '5', 5),
(215, 54, '1', 1),
(216, 54, '2', 2),
(217, 54, '3', 3),
(218, 54, '4', 4),
(219, 54, '5', 5),
(220, 55, '1', 1),
(221, 55, '2', 2),
(222, 55, '3', 3),
(223, 55, '4', 4),
(224, 55, '5', 5),
(225, 56, '1', 1),
(226, 56, '2', 2),
(227, 56, '3', 3),
(228, 56, '4', 4),
(229, 56, '5', 5),
(230, 57, '1', 1),
(231, 57, '2', 2),
(232, 57, '3', 3),
(233, 57, '4', 4),
(234, 57, '5', 5),
(235, 58, '1', 1),
(236, 58, '2', 2),
(237, 58, '3', 3),
(238, 58, '4', 4),
(239, 58, '5', 5),
(240, 59, '1', 1),
(241, 59, '2', 2),
(242, 59, '3', 3),
(243, 59, '4', 4),
(244, 59, '5', 5),
(245, 60, '1', 1),
(246, 60, '2', 2),
(247, 60, '3', 3),
(248, 60, '4', 4),
(249, 60, '5', 5),
(250, 61, '1', 1),
(251, 61, '2', 2),
(252, 61, '3', 3),
(253, 61, '4', 4),
(254, 61, '5', 5),
(255, 62, '1', 1),
(256, 62, '2', 2),
(257, 62, '3', 3),
(258, 62, '4', 4),
(259, 62, '5', 5),
(260, 63, '1', 1),
(261, 63, '2', 2),
(262, 63, '3', 3),
(263, 63, '4', 4),
(264, 63, '5', 5),
(265, 64, 'I clearly know myself', 1),
(266, 64, 'I know a little but not fully', 2),
(267, 64, 'I feel mostly confused', 3),
(268, 64, 'I avoid thinking about it', 4),
(269, 65, '1', 1),
(270, 65, '2', 2),
(271, 65, '3', 3),
(272, 65, '4', 4),
(273, 65, '5', 5),
(274, 66, '1', 1),
(275, 66, '2', 2),
(276, 66, '3', 3),
(277, 66, '4', 4),
(278, 66, '5', 5),
(279, 67, '1', 1),
(280, 67, '2', 2),
(281, 67, '3', 3),
(282, 67, '4', 4),
(283, 67, '5', 5),
(284, 68, '1', 1),
(285, 68, '2', 2),
(286, 68, '3', 3),
(287, 68, '4', 4),
(288, 68, '5', 5),
(289, 69, '1', 1),
(290, 69, '2', 2),
(291, 69, '3', 3),
(292, 69, '4', 4),
(293, 69, '5', 5),
(294, 70, '1', 1),
(295, 70, '2', 2),
(296, 70, '3', 3),
(297, 70, '4', 4),
(298, 70, '5', 5),
(299, 71, '1', 1),
(300, 71, '2', 2),
(301, 71, '3', 3),
(302, 71, '4', 4),
(303, 71, '5', 5),
(304, 72, 'Family advice', 1),
(305, 72, 'Marks & rank', 2),
(306, 72, 'Job security', 3),
(307, 72, 'Genuine interest', 4),
(308, 72, 'Lack of alternatives', 5),
(309, 73, '1', 1),
(310, 73, '2', 2),
(311, 73, '3', 3),
(312, 73, '4', 4),
(313, 73, '5', 5),
(314, 74, '1', 1),
(315, 74, '2', 2),
(316, 74, '3', 3),
(317, 74, '4', 4),
(318, 74, '5', 5),
(319, 75, '1', 1),
(320, 75, '2', 2),
(321, 75, '3', 3),
(322, 75, '4', 4),
(323, 75, '5', 5),
(324, 76, '1', 1),
(325, 76, '2', 2),
(326, 76, '3', 3),
(327, 76, '4', 4),
(328, 76, '5', 5),
(329, 77, '1', 1),
(330, 77, '2', 2),
(331, 77, '3', 3),
(332, 77, '4', 4),
(333, 77, '5', 5),
(334, 78, '1', 1),
(335, 78, '2', 2),
(336, 78, '3', 3),
(337, 78, '4', 4),
(338, 78, '5', 5),
(339, 79, '1', 1),
(340, 79, '2', 2),
(341, 79, '3', 3),
(342, 79, '4', 4),
(343, 79, '5', 5),
(344, 80, '1', 1),
(345, 80, '2', 2),
(346, 80, '3', 3),
(347, 80, '4', 4),
(348, 80, '5', 5),
(349, 81, '1', 1),
(350, 81, '2', 2),
(351, 81, '3', 3),
(352, 81, '4', 4),
(353, 81, '5', 5),
(354, 82, '1', 1),
(355, 82, '2', 2),
(356, 82, '3', 3),
(357, 82, '4', 4),
(358, 82, '5', 5),
(359, 83, '1', 1),
(360, 83, '2', 2),
(361, 83, '3', 3),
(362, 83, '4', 4),
(363, 83, '5', 5),
(364, 84, '1', 1),
(365, 84, '2', 2),
(366, 84, '3', 3),
(367, 84, '4', 4),
(368, 84, '5', 5),
(369, 85, '1', 1),
(370, 85, '2', 2),
(371, 85, '3', 3),
(372, 85, '4', 4),
(373, 85, '5', 5),
(374, 86, '1', 1),
(375, 86, '2', 2),
(376, 86, '3', 3),
(377, 86, '4', 4),
(378, 86, '5', 5),
(379, 87, 'They will choose a field with no financial stability', 1),
(380, 87, 'They will pick something trendy that becomes obsolete.', 2),
(381, 87, 'They will be unhappy or unfulfilled in their work.', 3),
(382, 87, 'They will not be able to make a decision at all.', 4),
(383, 88, 'Stable and traditional paths are still the safest.', 1),
(384, 88, 'Changing rapidly, difficult to predict.', 2),
(385, 88, 'Full of new-age opportunities we do not fully understand.', 3),
(386, 88, 'Competitive, requiring extra skills beyond a degree.', 4),
(387, 89, 'News channels and newspapers.', 1),
(388, 89, 'Social media (LinkedIn, Facebook groups).', 2),
(389, 89, 'Conversations with colleagues/friends.', 3),
(390, 89, 'Official education or government portals.', 4),
(391, 90, 'Skepticism - Is it a fad?', 1),
(392, 90, 'Curiosity - I want to research its scope.', 2),
(393, 90, 'Anxiety - Is my teen already behind?', 3),
(394, 90, 'Hope - This could be a great new opportunity.', 4),
(395, 91, 'Still the most reliable and respected path.', 1),
(396, 91, 'Valuable, but not the only option anymore.', 2),
(397, 91, 'Becoming less relevant compared to skill-based roles.', 3),
(398, 91, 'Too stressful and not worth the effort unless the child is passionate.', 4),
(399, 92, 'Too risky; should get a job first for experience.', 1),
(400, 92, 'A good idea if they have a solid plan and mentorship.', 2),
(401, 92, 'The future; better than being a salaried employee.', 3),
(402, 92, 'I don\'t know enough to guide them on this.', 4),
(403, 93, 'Job security and growth in a stable industry.', 1),
(404, 93, 'High salary potential.', 2),
(405, 93, 'Opportunities for innovation and entrepreneurship.', 3),
(406, 93, 'Global demand and chances to work abroad.', 4),
(407, 94, 'A scientific test to tell you the \\\"best\\\" career.', 1),
(408, 94, 'A tool to understand personality and interests, not dictate a career.', 2),
(409, 94, 'A modern gimmick with limited real-world use.', 3),
(410, 94, 'I\'ve heard of it but don\'t know how it works or its value.', 4),
(411, 95, 'Yes, if it\'s conducted by a certified professional', 1),
(412, 95, 'Maybe, as one of many inputs, not the sole decider.', 2),
(413, 95, 'No, I don\'t trust these tests.', 3),
(414, 95, 'No, my teen\'s interests are already clear.', 4),
(415, 96, 'The teen, based on their passion and research.', 1),
(416, 96, 'The parents, based on experience and practicality.', 2),
(417, 96, 'A career counselor, based on data and market trends.', 3),
(418, 96, 'A combination of all, through structured discussion.', 4),
(419, 97, 'Generation gap - we see the world differently', 1),
(420, 97, 'Lack of updated information from both sides.', 2),
(421, 97, 'Teen\'s disinterest or resistance to serious talk.', 3),
(422, 97, 'Emotional stress and fear of making the wrong choice.', 4),
(423, 98, 'Not at all prepared; things have changed too much.', 1),
(424, 98, 'Somewhat prepared, but need expert help.', 2),
(425, 98, 'Fairly prepared, I keep myself updated.', 3),
(426, 98, 'Completely prepared based on my own experience.', 4),
(427, 99, 'Worried that they are wasting time.', 1),
(428, 99, 'Empathetic; it\'s a normal phase.', 2),
(429, 99, 'Frustrated, and try to push them to decide.', 3),
(430, 99, 'Helpless, as I don\'t know how to help them explore.', 4),
(431, 100, 'Long-term financial prospects and stability.', 1),
(432, 100, 'Alignment with the teen\'s innate strengths.', 2),
(433, 100, 'Market demand and future growth projections.', 3),
(434, 100, 'Social prestige and family expectations.', 4),
(435, 101, 'Trust the college brochure/website.', 1),
(436, 101, 'Speak directly to alumni or current students.', 2),
(437, 101, 'Rely on third-party rankings and reports.', 3),
(438, 101, 'It\'s hard to get trustworthy data.', 4),
(439, 102, 'A waste of an academic year.', 1),
(440, 102, 'A luxury only some can afford.', 2),
(441, 102, 'A valuable time for clarity and experience.', 3),
(442, 102, 'Something I haven\'t considered.', 4),
(443, 103, 'Getting a good return on investment (ROI).', 1),
(444, 103, 'Being able to afford it without excessive loans.', 2),
(445, 103, 'The pressure it puts on the teen to succeed.', 3),
(446, 103, 'The fear of spending on a field they may leave later.', 4),
(447, 104, 'Significant pressure to choose a prestigious field.', 1),
(448, 104, 'Some pressure, but we try to ignore it.', 2),
(449, 104, 'No pressure; we make our own decisions.', 3),
(450, 104, 'The pressure comes more from within ourselves.', 4),
(451, 105, 'Want to take control and decide for them to reduce their stress.', 1),
(452, 105, 'Want to find a counselor or expert to help them.', 2),
(453, 105, 'Encourage them to relax, believing it will work out.', 3),
(454, 105, 'Feel equally stressed and anxious myself.', 4),
(455, 106, 'They are financially independent and secure.', 1),
(456, 106, 'They are working in a respected, established position.', 2),
(457, 106, 'They are happy and engaged in their daily work.', 3),
(458, 106, 'They have the flexibility and skills to adapt to changes.', 4),
(463, 108, 'Practicality must come first; passion can develop later.', 1),
(464, 108, 'A balance of both is essential.', 2),
(465, 108, 'Passion is critical; they will excel only if they love it.', 3),
(466, 108, 'It depends on our financial background.', 4),
(467, 109, 'Yes, mostly. Switching later is difficult.', 1),
(468, 109, 'No, career changes are common and acceptable now.', 2),
(469, 109, 'Hopefully, but they should be prepared to upskill.', 3),
(470, 109, 'I\'m concerned about the instability that thought brings.', 4),
(471, 110, 'Reliable, unbiased information on careers and courses.', 1),
(472, 110, 'Strategies to communicate effectively with my teen.', 2),
(473, 110, 'Access to a trustworthy mentor or counselor for my teen.', 3),
(474, 110, 'Reassurance that we are on the right track.', 4),
(475, 111, 'In Class 9-10, when choosing a stream.', 1),
(476, 111, 'In Class 11-12, when applying to colleges.', 2),
(477, 111, 'After Class 12, with more maturity.', 3),
(478, 111, 'It\'s a continuous process from childhood exposure.', 4),
(479, 112, 'A decision-maker to ensure a safe future.', 1),
(480, 112, 'A facilitator providing resources and opportunities.', 2),
(481, 112, 'A supportive guide who listens and encourages.', 3),
(482, 112, 'A financial planner for their education.', 4);

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `id` int(11) NOT NULL,
  `survey_id` int(11) NOT NULL,
  `question_text` text NOT NULL,
  `answer_type` enum('radio','checkbox') DEFAULT 'radio',
  `target_role` enum('Parent','School Student','College Student','Both') DEFAULT 'Both',
  `order_no` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `questions`
-- CORRECTED: Questions 38-64 are for School Students
-- Questions 65-86 are for College Students
-- Questions 87-112 are for Parents
--

INSERT INTO `questions` (`id`, `survey_id`, `question_text`, `answer_type`, `target_role`, `order_no`) VALUES
(38, 1, 'When someone asks, "What are you good at?" how confident do you feel answering?', 'radio', 'School Student', 1),
(39, 1, 'I feel I understand myself better than most people my age.', 'radio', 'School Student', 2),
(40, 1, 'I often feel confused about what kind of person I am becoming.', 'radio', 'School Student', 3),
(42, 1, 'I know my natural strengths beyond studies (communication, creativity, leadership, etc.).', 'radio', 'School Student', 4),
(43, 1, 'I feel confident in my abilities even when compared with others.', 'radio', 'School Student', 5),
(45, 1, 'When I succeed, I know why I succeeded.', 'radio', 'School Student', 6),
(46, 1, 'I clearly know what activities make me feel excited and energetic.', 'radio', 'School Student', 7),
(47, 1, 'I enjoy learning things outside school syllabus on my own.', 'radio', 'School Student', 8),
(48, 1, 'Sometimes I feel interested in things that adults around me don't value much.', 'radio', 'School Student', 9),
(49, 1, 'I feel confused about choosing the "right" stream or future path.', 'radio', 'School Student', 10),
(50, 1, 'I worry about making a wrong decision that I cannot change later.', 'radio', 'School Student', 11),
(51, 1, 'I feel pressured to choose something even before I fully understand myself.', 'radio', 'School Student', 12),
(52, 1, 'I know about multiple career options beyond common ones.', 'radio', 'School Student', 13),
(53, 1, 'I understand what different careers actually involve in real life.', 'radio', 'School Student', 14),
(54, 1, 'I feel school teaches me enough about real career choices.', 'radio', 'School Student', 15),
(55, 1, 'I feel stressed when thinking about my future.', 'radio', 'School Student', 16),
(56, 1, 'I feel scared of disappointing my parents or family.', 'radio', 'School Student', 17),
(57, 1, 'I compare myself with friends and feel anxious.', 'radio', 'School Student', 18),
(58, 1, 'I can clearly imagine myself 10 years from now.', 'radio', 'School Student', 19),
(59, 1, 'My future feels exciting rather than scary.', 'radio', 'School Student', 20),
(60, 1, 'If given guidance, I believe I can do well in life.', 'radio', 'School Student', 21),
(61, 1, 'I wish someone explained careers in a simple, honest way.', 'radio', 'School Student', 22),
(62, 1, 'I want guidance that understands me, not just my marks.', 'radio', 'School Student', 23),
(63, 1, 'I feel confident about who I am becoming as a person.', 'radio', 'School Student', 24),
(64, 1, 'Which statement best describes you right now?', 'radio', 'School Student', 25),
(65, 1, 'I feel clear about the direction my life is moving in.', 'radio', 'College Student', 26),
(66, 1, 'Sometimes I feel I am just following a path chosen earlier without clarity.', 'radio', 'College Student', 27),
(67, 1, 'I am confident about the skills I can offer to the real world.', 'radio', 'College Student', 28),
(68, 1, 'I know what I am naturally good at beyond my degree.', 'radio', 'College Student', 29),
(69, 1, 'I struggle to explain my strengths during interviews or discussions.', 'radio', 'College Student', 30),
(70, 1, 'My current course fully matches my interests.', 'radio', 'College Student', 31),
(71, 1, 'I sometimes regret my course choice.', 'radio', 'College Student', 32),
(72, 1, 'I chose my course mainly due to', 'radio', 'College Student', 33),
(73, 1, 'I feel anxious about getting the "right" career after college.', 'radio', 'College Student', 34),
(74, 1, 'I fear being stuck in a job I don't like.', 'radio', 'College Student', 35),
(75, 1, 'I worry about competition and job market uncertainty.', 'radio', 'College Student', 36),
(76, 1, 'My career decisions are strongly influenced by family expectations.', 'radio', 'College Student', 37),
(77, 1, 'I compare my progress with peers and feel pressure.', 'radio', 'College Student', 38),
(78, 1, 'I feel comfortable discussing my confusion openly with family.', 'radio', 'College Student', 39),
(79, 1, 'I fear changing direction even if I feel unsure now.', 'radio', 'College Student', 40),
(80, 1, 'I feel it is "too late" to rethink my career path.', 'radio', 'College Student', 41),
(81, 1, 'I wish I had better guidance earlier.', 'radio', 'College Student', 42),
(82, 1, 'I understand how my skills match real industry needs.', 'radio', 'College Student', 43),
(83, 1, 'I feel confused by too many career options and advice online.', 'radio', 'College Student', 44),
(84, 1, 'I know what steps I should take next for my career.', 'radio', 'College Student', 45),
(85, 1, 'If guided properly, I am open to re-aligning my career path.', 'radio', 'College Student', 46),
(86, 1, 'I believe understanding my strengths scientifically would help me.', 'radio', 'College Student', 47),
(87, 1, 'What is your biggest fear regarding your teen\'s career decision?', 'radio', 'Parent', 48),
(88, 1, 'How would you describe the current job market to your teen?', 'radio', 'Parent', 49),
(89, 1, 'What is your primary source of information about \"trending\" careers (e.g., AI, Data Science, Sustainability)?', 'radio', 'Parent', 50),
(90, 1, 'When you hear about a new \"trending course,\" what is your first reaction?', 'radio', 'Parent', 51),
(91, 1, 'How do you view a traditional professional degree (Engineering, Medicine, CA) today?', 'radio', 'Parent', 52),
(92, 1, 'What is your opinion about your teen starting their own business or venture?', 'radio', 'Parent', 53),
(93, 1, 'The concept of \"scope\" in a field mainly means to you:', 'radio', 'Parent', 54),
(94, 1, 'What is your understanding of a \"Psychometric Assessment\"?', 'radio', 'Parent', 55),
(95, 1, 'Would you consider a psychometric assessment for your teen?', 'radio', 'Parent', 56),
(96, 1, 'Who should have the MOST influence on the final career decision?', 'radio', 'Parent', 57),
(97, 1, 'What is the biggest barrier in discussing careers with your teen?', 'radio', 'Parent', 58),
(98, 1, 'How prepared do you feel to guide your teen in today\'s career landscape?', 'radio', 'Parent', 59),
(99, 1, 'When your teen says \"I don\'t know what I want to do,\" you feel:', 'radio', 'Parent', 60),
(100, 1, 'Which factor do you weigh most heavily when evaluating a career option?', 'radio', 'Parent', 61),
(101, 1, 'How do you validate information about a course\'s \"placement statistics\"?', 'radio', 'Parent', 62),
(102, 1, 'The idea of a \"gap year\" for career exploration is:', 'radio', 'Parent', 63),
(103, 1, 'What is your main concern about the cost of professional education?', 'radio', 'Parent', 64),
(104, 1, 'How much pressure do you feel from family/society regarding your teen\'s career choice?', 'radio', 'Parent', 65),
(105, 1, 'Seeing your teen stressed about career decisions makes you:', 'radio', 'Parent', 66),
(106, 1, 'In 10 years, what would you consider a \"successful\" outcome of this career decision?', 'radio', 'Parent', 67),
(108, 1, 'How important is \"passion\" versus \"practicality\" in your view?', 'radio', 'Parent', 68),
(109, 1, 'Do you believe the career your teen chooses now is for life?', 'radio', 'Parent', 69),
(110, 1, 'What support do you feel YOU need most right now?', 'radio', 'Parent', 70),
(111, 1, 'When should serious career planning ideally begin?', 'radio', 'Parent', 71),
(112, 1, 'Ultimately, my role as a parent in this process should be:', 'radio', 'Parent', 72);

-- --------------------------------------------------------

--
-- Table structure for table `surveys`
--

CREATE TABLE `surveys` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `status` tinyint(4) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `surveys`
--

INSERT INTO `surveys` (`id`, `title`, `status`, `created_at`) VALUES
(1, 'CareerG Survey 2026', 1, '2026-01-29 07:54:52');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `education` varchar(100) DEFAULT NULL,
  `role` enum('Parent','School Student','College Student','Student') DEFAULT 'Student',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `phone`, `name`, `email`, `age`, `education`, `role`, `created_at`) VALUES
(1, '9941428273', 'Stephen', NULL, 77, 'BE', 'Student', '2026-01-29 08:20:37'),
(2, '9941428273', 'Stephen', NULL, 55, 'BE', 'Student', '2026-01-29 08:25:30'),
(3, '9941428273', 'Stephen', NULL, 45, 'BE', 'Student', '2026-01-29 08:30:36'),
(4, '9941428273', 'Stephen', 'stephen@ithots.com', 34, '34', 'Parent', '2026-01-29 08:56:23'),
(5, '9941428273', 'Stephen', 'stephen@ithots.com', 4, 'BE', 'Parent', '2026-01-30 03:51:35'),
(6, '9941428273', 'Stephen', 'stephen@ithots.com', 12, 'BE', 'Student', '2026-01-30 04:07:28'),
(7, '9941428273', 'Stephen', 'stephen@ithots.com', 0, 'N/A', 'Parent', '2026-01-30 04:10:32'),
(8, '9941428273', 'Stephen', 'stephen@ithots.com', 12, 'BE', 'Student', '2026-01-30 04:12:08'),
(9, '9941428273', 'Stephen', 'stephen@ithots.com', 23, 'BE', '', '2026-01-30 05:17:31'),
(10, '9941428273', 'Stephen', 'stephen@ithots.com', 0, 'N/A', 'Parent', '2026-01-30 05:43:44'),
(11, '9941428273', 'Stephen', 'stephen@ithots.com', 12, 'BE', '', '2026-01-30 06:11:51'),
(12, '9941428273', 'Stephen', 'stephen@ithots.com', 0, 'N/A', 'Parent', '2026-01-30 06:13:55'),
(13, '09500813038', 'vishnu priya S', 'priya.vishnu059@gmail.com', 0, 'N/A', 'Parent', '2026-01-30 09:03:58'),
(14, '9487848989', 'sujitha', 'sujimeenu@gmail.com', 0, 'N/A', 'Parent', '2026-01-30 09:07:56'),
(15, '9823134242', 'sarah', 'sarah@gmail.com', 0, 'N/A', 'Parent', '2026-01-30 09:15:34'),
(16, '9823134242', 'sarah', 'sarah@gmail.com', 0, 'N/A', 'Parent', '2026-01-30 09:16:43'),
(17, '9823134242', 'sarah', 'sarah@gmail.com', 14, '9', '', '2026-01-30 09:27:57');

-- --------------------------------------------------------

--
-- Table structure for table `user_answers`
--

CREATE TABLE `user_answers` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `survey_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `answer_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_answers`
--

INSERT INTO `user_answers` (`id`, `user_id`, `survey_id`, `question_id`, `answer_id`, `created_at`) VALUES
(6, 11, 1, 38, 136, '2026-01-30 06:11:57'),
(7, 11, 1, 39, 142, '2026-01-30 06:12:01'),
(8, 11, 1, 40, 147, '2026-01-30 06:12:03'),
(9, 11, 1, 42, 157, '2026-01-30 06:12:06'),
(10, 11, 1, 43, 162, '2026-01-30 06:12:10'),
(11, 11, 1, 45, 173, '2026-01-30 06:12:13'),
(12, 13, 1, 87, 381, '2026-01-30 09:04:11'),
(25, 14, 1, 88, 384, '2026-01-30 09:14:25'),
(26, 14, 1, 89, 389, '2026-01-30 09:14:26'),
(27, 14, 1, 90, 392, '2026-01-30 09:14:29'),
(28, 14, 1, 91, 395, '2026-01-30 09:14:31'),
(29, 14, 1, 87, 381, '2026-01-30 09:14:58'),
(30, 15, 1, 87, 379, '2026-01-30 09:15:37'),
(39, 16, 1, 87, 380, '2026-01-30 09:20:48'),
(51, 16, 1, 88, 384, '2026-01-30 09:22:13'),
(52, 16, 1, 89, 388, '2026-01-30 09:22:13'),
(53, 16, 1, 90, 392, '2026-01-30 09:22:14'),
(54, 16, 1, 91, 395, '2026-01-30 09:22:15'),
(55, 16, 1, 92, 400, '2026-01-30 09:22:15'),
(56, 16, 1, 93, 403, '2026-01-30 09:22:15'),
(57, 16, 1, 94, 407, '2026-01-30 09:22:16'),
(60, 16, 1, 95, 411, '2026-01-30 09:22:20'),
(61, 16, 1, 96, 418, '2026-01-30 09:22:24'),
(62, 16, 1, 97, 420, '2026-01-30 09:22:54'),
(63, 16, 1, 98, 424, '2026-01-30 09:23:07'),
(64, 16, 1, 99, 428, '2026-01-30 09:23:41'),
(65, 16, 1, 100, 432, '2026-01-30 09:23:52'),
(66, 16, 1, 101, 435, '2026-01-30 09:24:14'),
(67, 16, 1, 102, 441, '2026-01-30 09:24:37'),
(68, 16, 1, 103, 446, '2026-01-30 09:24:50'),
(69, 16, 1, 104, 448, '2026-01-30 09:25:09'),
(70, 16, 1, 105, 453, '2026-01-30 09:25:20'),
(71, 16, 1, 106, 458, '2026-01-30 09:25:34'),
(72, 16, 1, 108, 464, '2026-01-30 09:25:51'),
(73, 16, 1, 109, 469, '2026-01-30 09:26:34'),
(74, 16, 1, 110, 473, '2026-01-30 09:26:45'),
(75, 16, 1, 111, 477, '2026-01-30 09:27:01'),
(76, 16, 1, 112, 481, '2026-01-30 09:27:21'),
(77, 17, 1, 38, 138, '2026-01-30 09:28:15'),
(78, 17, 1, 39, 144, '2026-01-30 09:28:21'),
(79, 17, 1, 40, 148, '2026-01-30 09:28:29'),
(80, 17, 1, 42, 157, '2026-01-30 09:28:57'),
(81, 17, 1, 43, 162, '2026-01-30 09:29:03'),
(82, 17, 1, 45, 172, '2026-01-30 09:29:09'),
(83, 17, 1, 46, 177, '2026-01-30 09:29:27'),
(84, 17, 1, 47, 183, '2026-01-30 09:29:32'),
(85, 17, 1, 48, 188, '2026-01-30 09:29:40'),
(86, 17, 1, 49, 192, '2026-01-30 09:29:50'),
(87, 17, 1, 50, 197, '2026-01-30 09:29:56'),
(88, 17, 1, 51, 201, '2026-01-30 09:30:03'),
(89, 17, 1, 52, 207, '2026-01-30 09:30:12'),
(90, 17, 1, 53, 213, '2026-01-30 09:30:20'),
(91, 17, 1, 54, 217, '2026-01-30 09:30:29'),
(92, 17, 1, 55, 222, '2026-01-30 09:30:33'),
(93, 17, 1, 56, 226, '2026-01-30 09:30:38'),
(94, 17, 1, 57, 232, '2026-01-30 09:30:45'),
(95, 17, 1, 58, 237, '2026-01-30 09:30:49'),
(96, 17, 1, 59, 242, '2026-01-30 09:30:54'),
(97, 17, 1, 60, 248, '2026-01-30 09:31:00'),
(98, 17, 1, 61, 252, '2026-01-30 09:31:07'),
(101, 17, 1, 62, 259, '2026-01-30 09:32:04'),
(102, 17, 1, 63, 262, '2026-01-30 09:32:07'),
(103, 17, 1, 64, 266, '2026-01-30 09:32:20');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `answers`
--
ALTER TABLE `answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `question_id` (`question_id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `survey_id` (`survey_id`);

--
-- Indexes for table `surveys`
--
ALTER TABLE `surveys`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_answers`
--
ALTER TABLE `user_answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `survey_id` (`survey_id`),
  ADD KEY `question_id` (`question_id`),
  ADD KEY `answer_id` (`answer_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `answers`
--
ALTER TABLE `answers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=483;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=113;

--
-- AUTO_INCREMENT for table `surveys`
--
ALTER TABLE `surveys`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `user_answers`
--
ALTER TABLE `user_answers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=104;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `answers`
--
ALTER TABLE `answers`
  ADD CONSTRAINT `answers_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`survey_id`) REFERENCES `surveys` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_answers`
--
ALTER TABLE `user_answers`
  ADD CONSTRAINT `user_answers_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_answers_ibfk_2` FOREIGN KEY (`survey_id`) REFERENCES `surveys` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_answers_ibfk_3` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_answers_ibfk_4` FOREIGN KEY (`answer_id`) REFERENCES `answers` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
