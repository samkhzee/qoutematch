-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 03, 2026 at 09:34 AM
-- Server version: 8.0.30
-- PHP Version: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `olance`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `username` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `email`, `username`, `email_verified_at`, `image`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Super Admins', 'admin@site.com', 'admin', NULL, '679deac994b191738402505.png', '$2y$12$vc.c.pNxefhOjFzLFNMEW.16i/h1vQCigtZeTLDY12QlIlS0KTWbm', NULL, NULL, '2025-02-01 03:35:05');

-- --------------------------------------------------------

--
-- Table structure for table `admin_notifications`
--

CREATE TABLE `admin_notifications` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL DEFAULT '0',
  `buyer_id` int NOT NULL DEFAULT '0',
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `click_url` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admin_password_resets`
--

CREATE TABLE `admin_password_resets` (
  `id` bigint UNSIGNED NOT NULL,
  `email` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `token` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `badge_settings`
--

CREATE TABLE `badge_settings` (
  `id` bigint NOT NULL,
  `min_amount` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `badge_name` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bids`
--

CREATE TABLE `bids` (
  `id` bigint NOT NULL,
  `job_id` int NOT NULL DEFAULT '0',
  `user_id` int NOT NULL DEFAULT '0' COMMENT 'freelancer id',
  `buyer_id` int NOT NULL DEFAULT '0',
  `project_id` int NOT NULL DEFAULT '0',
  `bid_quote` text COLLATE utf8mb4_unicode_ci,
  `bid_amount` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `estimated_time` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_shortlist` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0->NO, 1-> YES',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '''pending''=>0, ''accepted''=>1, ''rejected''=>3, ''withdrawn''=>4, \r\n''completed'' => 2,',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `buyers`
--

CREATE TABLE `buyers` (
  `id` bigint UNSIGNED NOT NULL,
  `firstname` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lastname` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `username` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dial_code` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `balance` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country_code` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0: banned, 1: active',
  `image` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kyc_data` text COLLATE utf8mb4_unicode_ci,
  `kyc_rejection_reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kv` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0: KYC Unverified, 2: KYC pending, 1: KYC verified',
  `ev` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0: email unverified, 1: email verified',
  `sv` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0: mobile unverified, 1: mobile verified',
  `profile_complete` tinyint(1) NOT NULL DEFAULT '0',
  `ver_code` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'stores verification code',
  `ver_code_send_at` datetime DEFAULT NULL COMMENT 'verification send time',
  `ts` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0: 2fa off, 1: 2fa on',
  `tv` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0: 2fa unverified, 1: 2fa verified',
  `tsc` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ban_reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `language` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avg_rating` decimal(5,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `buyer_password_resets`
--

CREATE TABLE `buyer_password_resets` (
  `id` bigint UNSIGNED NOT NULL,
  `email` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `token` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `buyer_reviews`
--

CREATE TABLE `buyer_reviews` (
  `id` bigint NOT NULL,
  `buyer_id` int NOT NULL DEFAULT '0',
  `user_id` int NOT NULL DEFAULT '0' COMMENT 'How given review',
  `project_id` int NOT NULL DEFAULT '0',
  `rating` int NOT NULL DEFAULT '0' COMMENT 'Buyer give this rating',
  `review` text COLLATE utf8mb4_unicode_ci COMMENT 'Buyer give this review',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(40) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `charges`
--

CREATE TABLE `charges` (
  `id` bigint NOT NULL,
  `level` int NOT NULL DEFAULT '0',
  `amount` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `percent` decimal(5,2) NOT NULL DEFAULT '0.00',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `conversations`
--

CREATE TABLE `conversations` (
  `id` bigint NOT NULL,
  `buyer_id` int NOT NULL DEFAULT '0',
  `user_id` int NOT NULL DEFAULT '0' COMMENT 'Talent / Freelancer id',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Block -> 1, Unblock ->0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `deposits`
--

CREATE TABLE `deposits` (
  `id` bigint UNSIGNED NOT NULL,
  `buyer_id` int NOT NULL DEFAULT '0',
  `method_code` int UNSIGNED NOT NULL DEFAULT '0',
  `amount` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `method_currency` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `charge` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `rate` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `final_amount` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `detail` text COLLATE utf8mb4_unicode_ci,
  `btc_amount` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `btc_wallet` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trx` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_try` int NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1=>success, 2=>pending, 3=>cancel',
  `from_api` tinyint(1) NOT NULL DEFAULT '0',
  `is_web` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'This will be 1 if the request is from NextJs application',
  `admin_feedback` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `success_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `failed_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_cron` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `device_tokens`
--

CREATE TABLE `device_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL DEFAULT '0',
  `buyer_id` int NOT NULL DEFAULT '0',
  `is_app` tinyint(1) NOT NULL DEFAULT '0',
  `token` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `education`
--

CREATE TABLE `education` (
  `id` bigint NOT NULL,
  `user_id` int NOT NULL DEFAULT '0',
  `school` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `year_from` year DEFAULT NULL,
  `year_to` year DEFAULT NULL,
  `degree` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `area_of_study` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `extensions`
--

CREATE TABLE `extensions` (
  `id` bigint UNSIGNED NOT NULL,
  `act` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `script` text COLLATE utf8mb4_unicode_ci,
  `shortcode` text COLLATE utf8mb4_unicode_ci COMMENT 'object',
  `support` text COLLATE utf8mb4_unicode_ci COMMENT 'help section',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1=>enable, 2=>disable',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `extensions`
--

INSERT INTO `extensions` (`id`, `act`, `name`, `description`, `image`, `script`, `shortcode`, `support`, `status`, `created_at`, `updated_at`) VALUES
(1, 'tawk-chat', 'Tawk.to', 'Key location is shown bellow', 'tawky_big.png', '<script>\r\n                        var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();\r\n                        (function(){\r\n                        var s1=document.createElement(\"script\"),s0=document.getElementsByTagName(\"script\")[0];\r\n                        s1.async=true;\r\n                        s1.src=\"https://embed.tawk.to/{{app_key}}\";\r\n                        s1.charset=\"UTF-8\";\r\n                        s1.setAttribute(\"crossorigin\",\"*\");\r\n                        s0.parentNode.insertBefore(s1,s0);\r\n                        })();\r\n                    </script>', '{\"app_key\":{\"title\":\"App Key\",\"value\":\"------\"}}', 'twak.png', 0, '2019-10-18 11:16:05', '2024-05-16 06:23:02'),
(2, 'google-recaptcha2', 'Google Recaptcha 2', 'Key location is shown bellow', 'recaptcha3.png', '\n<script src=\"https://www.google.com/recaptcha/api.js\"></script>\n<div class=\"g-recaptcha\" data-sitekey=\"{{site_key}}\" data-callback=\"verifyCaptcha\"></div>\n<div id=\"g-recaptcha-error\"></div>', '{\"site_key\":{\"title\":\"Site Key\",\"value\":\"----------------------------\"},\"secret_key\":{\"title\":\"Secret Key\",\"value\":\"----------------------------\"}}', 'recaptcha.png', 0, '2019-10-18 11:16:05', '2026-01-03 03:09:27'),
(3, 'custom-captcha', 'Custom Captcha', 'Just put any random string', 'customcaptcha.png', NULL, '{\"random_key\":{\"title\":\"Random String\",\"value\":\"SecureString\"}}', 'na', 0, '2019-10-18 11:16:05', '2024-12-25 05:08:56'),
(4, 'google-analytics', 'Google Analytics', 'Key location is shown bellow', 'google_analytics.png', '<script async src=\"https://www.googletagmanager.com/gtag/js?id={{measurement_id}}\"></script>\n                <script>\n                  window.dataLayer = window.dataLayer || [];\n                  function gtag(){dataLayer.push(arguments);}\n                  gtag(\"js\", new Date());\n                \n                  gtag(\"config\", \"{{measurement_id}}\");\n                </script>', '{\"measurement_id\":{\"title\":\"Measurement ID\",\"value\":\"------\"}}', 'ganalytics.png', 0, NULL, '2021-05-03 22:19:12'),
(5, 'fb-comment', 'Facebook Comment ', 'Key location is shown bellow', 'Facebook.png', '<div id=\"fb-root\"></div><script async defer crossorigin=\"anonymous\" src=\"https://connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v4.0&appId={{app_key}}&autoLogAppEvents=1\"></script>', '{\"app_key\":{\"title\":\"App Key\",\"value\":\"----\"}}', 'fb_com.png', 0, NULL, '2022-03-21 17:18:36');

-- --------------------------------------------------------

--
-- Table structure for table `forms`
--

CREATE TABLE `forms` (
  `id` bigint UNSIGNED NOT NULL,
  `act` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `form_data` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `frontends`
--

CREATE TABLE `frontends` (
  `id` bigint UNSIGNED NOT NULL,
  `data_keys` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data_values` longtext COLLATE utf8mb4_unicode_ci,
  `seo_content` longtext COLLATE utf8mb4_unicode_ci,
  `tempname` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `frontends`
--

INSERT INTO `frontends` (`id`, `data_keys`, `data_values`, `seo_content`, `tempname`, `slug`, `created_at`, `updated_at`) VALUES
(1, 'seo.data', '{\"seo_image\":\"1\",\"keywords\":[\"olance\",\"freelancing\",\"bid\",\"job post\",\"bid project\",\"earning\",\"viserlab\"],\"description\":\"Olance is a dynamic freelancing platform connecting clients with skilled professionals across various industries. With secure transactions, a user-friendly interface, and advanced project management tools, Olance simplifies remote work and collaboration. Whether you\'re hiring or offering services, Olance provides a seamless experience for freelancers and businesses alike.\",\"social_title\":\"Global Freelancing Marketplace\",\"social_description\":\"Olance is a dynamic freelancing platform connecting clients with skilled professionals across various industries. With secure transactions, a user-friendly interface, and advanced project management tools, Olance simplifies remote work and collaboration. Whether you\'re hiring or offering services, Olance provides a seamless experience for freelancers and businesses alike.\",\"image\":\"679de5c787bc51738401223.png\"}', NULL, NULL, '', '2020-07-04 23:42:52', '2025-02-01 03:32:22'),
(24, 'about.content', '{\"has_image\":\"1\",\"heading\":\"Simple and Easy to Find Freelancer or Great Job\",\"image\":\"67d931272de8c1742287143.png\"}', NULL, 'basic', '', '2020-10-28 00:51:20', '2025-03-18 02:39:03'),
(25, 'blog.content', '{\"heading\":\"Our Latest Blog Post\",\"subheading\":\"Stay updated with the latest insights, tips, and trends from our expert team.\"}', NULL, 'basic', '', '2020-10-28 00:51:34', '2025-03-18 04:04:41'),
(27, 'contact_us.content', '{\"heading\":\"Let`s Talk with US\",\"subheading\":\"Disregard the old standards. You can have the best individuals. At this moment. Here.\",\"title\":\"Contact with Us\",\"email_address\":\"democompany@gmail.com\",\"contact_number\":\"+1-666-0121\",\"contact_details\":\"2118 Thornridge Cir. Syracuse\"}', NULL, 'basic', '', '2020-10-28 00:59:19', '2025-01-30 03:08:10'),
(28, 'counter.content', '{\"heading\":\"Latest News\",\"subheading\":\"Lorem ipsum dolor sit, amet consectetur adipisicing elit. Doloribus necessitatibus repudiandae porro reprehenderit, beatae perferendis repellat quo ipsa omnis, vitae!\"}', NULL, 'basic', NULL, '2020-10-28 01:04:02', '2024-03-13 23:54:07'),
(31, 'social_icon.element', '{\"title\":\"Facebook\",\"social_icon\":\"<i class=\\\"fab fa-facebook-f\\\"><\\/i>\",\"url\":\"https:\\/\\/www.facebook.com\\/\"}', NULL, 'basic', '', '2020-11-12 04:07:30', '2024-11-18 23:33:42'),
(33, 'feature.content', '{\"heading\":\"asdf\",\"sub_heading\":\"asdf\"}', NULL, 'basic', NULL, '2021-01-03 23:40:54', '2021-01-03 23:40:55'),
(34, 'feature.element', '{\"title\":\"asdf\",\"description\":\"asdf\",\"feature_icon\":\"asdf\"}', NULL, 'basic', NULL, '2021-01-03 23:41:02', '2021-01-03 23:41:02'),
(35, 'service.element', '{\"trx_type\":\"withdraw\",\"service_icon\":\"<i class=\\\"las la-highlighter\\\"><\\/i>\",\"title\":\"asdfasdf\",\"description\":\"asdfasdfasdfasdf\"}', NULL, 'basic', NULL, '2021-03-06 01:12:10', '2021-03-06 01:12:10'),
(36, 'service.content', '{\"trx_type\":\"deposit\",\"heading\":\"asdf fffff\",\"subheading\":\"555\"}', NULL, 'basic', NULL, '2021-03-06 01:27:34', '2022-03-30 08:07:06'),
(41, 'cookie.data', '{\"short_desc\":\"We may use cookies or any other tracking technologies when you visit our website, including any other media form, mobile website, or mobile application related or connected to help customize the Site and improve your experience.\",\"description\":\"<h5>Cookie Policy<\\/h5>\\r\\n\\r\\n<p>This Cookie Policy explains how to use cookies and similar technologies to recognize you when you visit our website. It explains what these technologies are and why we use them, as well as your rights to control our use of them.<\\/p>\\r\\n<br>\\r\\n<h5>What are cookies?<\\/h5>\\r\\n\\r\\n<p>Cookies are small pieces of data stored on your computer or mobile device when you visit a website. Cookies are widely used by website owners to make their websites work, or to work more efficiently, as well as to provide reporting information.<\\/p>\\r\\n<br>\\r\\n<h5>Why do we use cookies?<\\/h5>\\r\\n\\r\\n<p>We use cookies for several reasons. Some cookies are required for technical reasons for our Website to operate, and we refer to these as \\\"essential\\\" or \\\"strictly necessary\\\" cookies. Other cookies enable us to track and target the interests of our users to enhance the experience on our Website. Third parties serve cookies through our Website for advertising, analytics, and other purposes.<\\/p>\\r\\n<br>\\r\\n<h5>What types of cookies do we use?<\\/h5>\\r\\n\\r\\n<div>\\r\\n    <ul style=\\\"list-style: unset;\\\">\\r\\n        <li>\\r\\n            <strong>Essential Website Cookies:<\\/strong> \\r\\n            These cookies are strictly necessary to provide you with services available through our Website and to use some of its features.\\r\\n        <\\/li>\\r\\n        <li>\\r\\n            <strong>Analytics and Performance Cookies:<\\/strong> \\r\\n            These cookies allow us to count visits and traffic sources to measure and improve our Website\'s performance.\\r\\n        <\\/li>\\r\\n        <li>\\r\\n            <strong>Advertising Cookies:<\\/strong> \\r\\n            These cookies make advertising messages more relevant to you and your interests. They perform functions like preventing the same ad from continuously reappearing, ensuring that ads are properly displayed, and in some cases selecting advertisements that are based on your interests.\\r\\n        <\\/li>\\r\\n    <\\/ul>\\r\\n<\\/div>\\r\\n<br>\\r\\n<h5>Data Collected by Cookies<\\/h5>\\r\\n<p>Cookies may collect various types of data, including but not limited to:<\\/p>\\r\\n<ul style=\\\"list-style: unset;\\\">\\r\\n    <li>IP addresses<\\/li>\\r\\n    <li>Browser and device information<\\/li>\\r\\n    <li>Referring website addresses<\\/li>\\r\\n    <li>Pages visited on our website<\\/li>\\r\\n    <li>Interactions with our website, such as clicks and mouse movements<\\/li>\\r\\n    <li>Time spent on our website<\\/li>\\r\\n<\\/ul>\\r\\n<br>\\r\\n<h5>How We Use Collected Data<\\/h5>\\r\\n\\r\\n<p>We may use data collected by cookies for the following purposes:<\\/p>\\r\\n<ul style=\\\"list-style: unset;\\\">\\r\\n    <li>To personalize your experience on our website<\\/li>\\r\\n    <li>To improve our website\'s functionality and performance<\\/li>\\r\\n    <li>To analyze trends and gather demographic information about our user base<\\/li>\\r\\n    <li>To deliver targeted advertising based on your interests<\\/li>\\r\\n    <li>To prevent fraudulent activity and enhance website security<\\/li>\\r\\n<\\/ul>\\r\\n<br>\\r\\n<h5>Third-party cookies<\\/h5>\\r\\n\\r\\n<p>In addition to our cookies, we may also use various third-party cookies to report usage statistics of our Website, deliver advertisements on and through our Website, and so on.<\\/p>\\r\\n<br>\\r\\n<h5>How can we control cookies?<\\/h5>\\r\\n\\r\\n<p>You have the right to decide whether to accept or reject cookies. You can exercise your cookie preferences by clicking on the \\\"Cookie Settings\\\" link in the footer of our website. You can also set or amend your web browser controls to accept or refuse cookies. If you choose to reject cookies, you may still use our Website though your access to some functionality and areas of our Website may be restricted.<\\/p>\\r\\n<br>\\r\\n<h5>Changes to our Cookie Policy<\\/h5>\\r\\n\\r\\n<p>We may update our Cookie Policy from time to time. We will notify you of any changes by posting the new Cookie Policy on this page.<\\/p>\",\"status\":1}', NULL, NULL, NULL, '2020-07-04 23:42:52', '2025-01-21 23:25:37'),
(42, 'policy_pages.element', '{\"title\":\"Privacy Policy\",\"details\":\"<h5>Introduction<\\/h5>\\r\\n        <p>\\r\\n            This Privacy Policy describes how we collects, uses, and discloses information, including personal information, in connection with your use of our website.\\r\\n        <\\/p>\\r\\n        <br \\/>\\r\\n        <h5>Information We Collect<\\/h5>\\r\\n        <p>We collect two main types of information on the Website:<\\/p>\\r\\n        <ul>\\r\\n            <li><p><strong>Personal Information: <\\/strong>This includes data that can identify you as an individual, such as your name, email address, phone number, or mailing address. We only collect this information when you voluntarily provide it to us, like signing up for a newsletter, contacting us through a form, or making a purchase.<\\/p><\\/li>\\r\\n            <li><p><strong>Non-Personal Information: <\\/strong>This data cannot be used to identify you directly. It includes details like your browser type, device type, operating system, IP address, browsing activity, and usage statistics. We collect this information automatically through cookies and other tracking technologies.<\\/p><\\/li>\\r\\n        <\\/ul>\\r\\n        <br \\/>\\r\\n        <h5>How We Use Information<\\/h5>\\r\\n        <p>The information we collect allows us to:<\\/p>\\r\\n        <ul>\\r\\n            <li>Operate and maintain the Website effectively.<\\/li>\\r\\n            <li>Send you newsletters or marketing communications, but only with your consent.<\\/li>\\r\\n            <li>Respond to your inquiries and fulfill your requests.<\\/li>\\r\\n            <li>Improve the Website and your user experience.<\\/li>\\r\\n            <li>Personalize your experience on the Website based on your browsing habits.<\\/li>\\r\\n            <li>Analyze how the Website is used to improve our services.<\\/li>\\r\\n            <li>Comply with legal and regulatory requirements.<\\/li>\\r\\n        <\\/ul>\\r\\n        <br \\/>\\r\\n        <h5>Sharing of Information<\\/h5>\\r\\n        <p>We may share your information with trusted third-party service providers who assist us in operating the Website and delivering our services. These providers are obligated by contract to keep your information confidential and use it only for the specific purposes we disclose it for.<\\/p>\\r\\n        <p>We will never share your personal information with any third parties for marketing purposes without your explicit consent.<\\/p>\\r\\n        <br \\/>\\r\\n        <h5>Data Retention<\\/h5>\\r\\n        <p>We retain your personal information only for as long as necessary to fulfill the purposes it was collected for. We may retain it for longer periods only if required or permitted by law.<\\/p>\\r\\n        <br \\/>\\r\\n        <h5>Security Measures<\\/h5>\\r\\n        <p>We take reasonable precautions to protect your information from unauthorized access, disclosure, alteration, or destruction. However, complete security cannot be guaranteed for any website or internet transmission.<\\/p>\\r\\n        <br \\/>\\r\\n        <h5>Changes to this Privacy Policy<\\/h5>\\r\\n        <p>We may update this Privacy Policy periodically. We will notify you of any changes by posting the revised policy on the Website. We recommend reviewing this policy regularly to stay informed of any updates.<\\/p>\\r\\n        <p><strong>Remember:<\\/strong>  This is a sample policy and may need adjustments to comply with specific laws and reflect your website\'s unique data practices. Consider consulting with a legal professional to ensure your policy is fully compliant.<\\/p>\"}', '{\"image\":null,\"description\":null,\"social_title\":null,\"social_description\":null,\"keywords\":null}', 'basic', 'privacy-policy', '2021-06-09 08:50:42', '2025-01-21 23:25:01'),
(43, 'policy_pages.element', '{\"title\":\"Terms of Service\",\"details\":\"<div class=\\\"mb-5\\\">\\r\\n    <h5 class=\\\"mb-3\\\">What information do we collect?<\\/h5>\\r\\n    <p>We\\r\\n        gather data from you when you register on our site, submit a request,\\r\\n        buy any services, react to an overview, or round out a structure. At the\\r\\n        point when requesting any assistance or enrolling on our site, as\\r\\n        suitable, you might be approached to enter your: name, email address, or\\r\\n        telephone number. You may, nonetheless, visit our site anonymously.<\\/p>\\r\\n<\\/div>\\r\\n<div class=\\\"mb-5\\\">\\r\\n    <h5 class=\\\"mb-3\\\">How do we protect your information?<\\/h5>\\r\\n    <p>All provided delicate\\/credit data is\\r\\n        sent through Stripe.<br \\/>After\\r\\n        an exchange, your private data (credit cards, social security numbers,\\r\\n        financials, and so on) won\'t be put away on our workers.<\\/p>\\r\\n<\\/div>\\r\\n<div class=\\\"mb-5\\\">\\r\\n    <h5 class=\\\"mb-3\\\">Do we disclose any information to outside parties?<\\/h5>\\r\\n    <p>We\\r\\n        don\'t sell, exchange, or in any case, move to outside gatherings by and\\r\\n        by recognizable data. This does exclude confided-in outsiders who help\\r\\n        us in working our site, leading our business, or adjusting you since\\r\\n        those gatherings consent to keep this data private. We may likewise\\r\\n        deliver your data when we accept discharge is suitable to follow the\\r\\n        law, implement our site strategies, or ensure our own or others\' rights,\\r\\n        property, or wellbeing.<\\/p>\\r\\n<\\/div>\\r\\n<div class=\\\"mb-5\\\">\\r\\n    <h5 class=\\\"mb-3\\\">Children\'s Online Privacy Protection Act Compliance<\\/h5>\\r\\n    <p>We\\r\\n        are consistent with the prerequisites of COPPA (Children\'s Online\\r\\n        Privacy Protection Act), we don\'t gather any data from anybody under 13\\r\\n        years old. Our site, items, and administrations are completely\\r\\n        coordinated to individuals who are in any event 13 years of age or more\\r\\n        established.<\\/p>\\r\\n<\\/div>\\r\\n<div class=\\\"mb-5\\\">\\r\\n    <h5 class=\\\"mb-3\\\">\\r\\n        Changes to our Privacy Policy<\\/h5>\\r\\n    <p>If we decide to change our privacy\\r\\n        policy, we will post those changes on this page.<\\/p>\\r\\n<\\/div>\\r\\n<div class=\\\"mb-5\\\">\\r\\n    <h5 class=\\\"mb-3\\\">How long we retain your information?<\\/h5>\\r\\n    <p>At\\r\\n        the point when you register for our site, we cycle and keep the\\r\\n        information we have about you however long you don\'t erase the record or\\r\\n        withdraw yourself (subject to laws and guidelines).<\\/p>\\r\\n<\\/div>\\r\\n<div>\\r\\n    <h5 class=\\\"mb-3\\\">What we don\\u2019t do with your data<\\/h5>\\r\\n    <p>We\\r\\n        don\'t and will never share, unveil, sell, or in any case give your\\r\\n        information to different organizations for the promotion of their items\\r\\n        or administrations.<\\/p>\\r\\n<\\/div>\"}', '{\"image\":\"6635d5d9618e71714804185.png\",\"description\":null,\"social_title\":null,\"social_description\":null,\"keywords\":null}', 'basic', 'terms-of-service', '2021-06-09 08:51:18', '2025-03-18 23:11:33'),
(44, 'maintenance.data', '{\"description\":\"<div class=\\\"mb-5\\\" style=\\\"font-family: Nunito, sans-serif; margin-bottom: 3rem !important;\\\"><h3 class=\\\"mb-3\\\" style=\\\"text-align: center; font-weight: 600; line-height: 1.3; font-size: 24px; font-family: Exo, sans-serif;\\\"><font color=\\\"#ff0000\\\">THE SITE IS UNDER MAINTENANCE<\\/font><\\/h3><p class=\\\"font-18\\\" style=\\\"color: rgb(111, 111, 111); text-align: center; margin-right: 0px; margin-left: 0px; font-size: 18px !important;\\\">We\'re just tuning up a few things.We apologize for the inconvenience but Front is currently undergoing planned maintenance. Thanks for your patience.<\\/p><\\/div>\",\"image\":\"6603c203472ad1711522307.png\"}', NULL, NULL, NULL, '2020-07-04 23:42:52', '2024-03-27 06:51:47'),
(52, 'blog.element', '{\"has_image\":[\"1\"],\"title\":\"Exploring the Cryptocurrency Landscape: A Comprehensive Guide for Beginners.\",\"description\":\"<div>The landscape of service center automation has undergone a remarkable transformation over the past few decades,\\r\\n    shifting from reactive to proactive approaches. Initially, service centers operated on a reactive model, primarily\\r\\n    addressing issues only after they arose. This method often involved responding to customer complaints and\\r\\n    troubleshooting problems as they emerged, leading to higher levels of downtime and customer dissatisfaction. The\\r\\n    focus was largely on fixing problems rather than preventing them, which created a cycle of continuous reaction to\\r\\n    service issues.<\\/div>\\r\\n<div><br \\/><\\/div>\\r\\n<h6 class=\\\"m-0\\\">Shifting Paradigms: The Move Toward Proactive Service Models<\\/h6>\\r\\n<div>As technology advanced, the paradigm began to shift towards a more proactive approach. The rise of advanced data\\r\\n    analytics and machine learning techniques enabled service centers to anticipate potential issues before they\\r\\n    occurred. By analyzing historical data and recognizing patterns, service centers could now predict when equipment\\r\\n    might fail or when a system could encounter problems. This shift not only reduced the frequency of unexpected\\r\\n    disruptions but also improved overall operational efficiency. Predictive maintenance became a key feature of\\r\\n    proactive service centers, allowing them to schedule maintenance activities during non-peak hours and address issues\\r\\n    before they impacted the customer experience.<\\/div>\\r\\n<div><br \\/><\\/div>\\r\\n<blockquote style=\\\"font-style:italic;text-align:center;padding:20px;color:#464545;background:rgba(139,131,133,0.54);font-size:18px;border-left:4px solid rgb(223,52,89);\\\">\\r\\n    As technology continues to evolve, service centers are likely to adopt even more advanced and innovative solutions that enhance their ability to provide seamless and proactive service.<\\/blockquote>\\r\\n<h6 class=\\\"m-0\\\">Harnessing Data Analytics and Machine Learning for Predictive Maintenance<\\/h6>\\r\\n<div>This evolution was further accelerated by the integration of artificial intelligence (AI) and automation tools.\\r\\n    AI-driven systems can now monitor and analyze real-time data from various sources, providing insights into potential\\r\\n    issues and suggesting preventive measures. Automation tools facilitate swift responses to identified issues, often\\r\\n    without the need for human intervention. For instance, automated systems can initiate repairs or adjustments based\\r\\n    on predefined parameters, ensuring that minor issues are resolved before they escalate into significant problems.\\r\\n<\\/div>\\r\\n<div><br \\/><\\/div>\\r\\n<h6 class=\\\"m-0\\\">The Role of AI and Automation in Revolutionizing Service Center Operations<\\/h6>\\r\\n<div>The proactive approach not only enhances operational efficiency but also fosters a more positive customer\\r\\n    experience. By addressing potential issues before they impact customers, service centers can ensure a higher level\\r\\n    of service continuity and reliability. Customers benefit from fewer disruptions and a more streamlined experience,\\r\\n    leading to increased satisfaction and loyalty.<\\/div>\\r\\n<div><br \\/><\\/div>\\r\\n<h6 class=\\\"m-0\\\">Enhancing Customer Experience Through Proactive Service Strategies<\\/h6>\\r\\n<div>In essence, the evolution from a reactive to a proactive model in service center automation represents a\\r\\n    significant leap forward. It highlights the importance of leveraging advanced technologies to not only address\\r\\n    problems but to anticipate and prevent them, ultimately leading to a more efficient and customer-centric operation.\\r\\n    As technology continues to evolve, service centers will likely see even more sophisticated solutions that further\\r\\n    enhance their ability to provide seamless and proactive service.<\\/div>\\r\\n<div><br \\/><\\/div>\",\"image\":\"67d290bee76941741852862.png\"}', NULL, 'basic', 'exploring-the-cryptocurrency-landscape-a-comprehensive-guide-for-beginners', '2024-03-24 06:52:04', '2025-03-19 01:59:58'),
(55, 'counter.content', '{\"heading\":\"Latest Newsss\",\"subheading\":\"Lorem ipsum dolor sit, amet consectetur adipisicing elit. Doloribus necessitatibus repudiandae porro reprehenderit, beatae perferendis repellat quo ipsa omnis, vitae!\"}', NULL, 'basic', '', '2024-04-21 01:13:50', '2024-04-21 01:13:50'),
(56, 'counter.content', '{\"heading\":\"Latest News\",\"subheading\":\"Lorem ipsum dolor sit, amet consectetur adipisicing elit. Doloribus necessitatibus repudiandae porro reprehenderit, beatae perferendis repellat quo ipsa omnis, vitae!\"}', NULL, 'basic', '', '2024-04-21 01:13:52', '2024-04-21 01:13:52'),
(60, 'kyc.content', '{\"required\":\"Complete KYC to unlock the full potential of our platform! KYC helps us verify your identity and keep things secure. It is quick and easy just follow the on-screen instructions. Get started with KYC verification now!\",\"pending\":\"Your KYC verification is being reviewed. We might need some additional information. You will get an email update soon. In the meantime, explore our platform with limited features.\"}', NULL, 'basic', '', '2024-04-25 06:35:35', '2024-04-25 06:35:35'),
(61, 'kyc.content', '{\"required\":\"Complete KYC to unlock the full potential of our platform! KYC helps us verify your identity and keep things secure. It is quick and easy just follow the on-screen instructions. Get started with KYC verification now!\",\"pending\":\"Your KYC verification is being reviewed. We might need some additional information. You will get an email update soon. In the meantime, explore our platform with limited features.\",\"reject\":\"We regret to inform you that the Know Your Customer (KYC) information provided has been reviewed and unfortunately, it has not met our verification standards.\"}', NULL, 'basic', '', '2024-04-25 06:40:29', '2024-04-25 06:40:29'),
(64, 'banner.content', '{\"has_image\":\"1\",\"heading\":\"Find the Best Freelance Jobs\",\"subheading\":\"Connecting talent with opportunity \\u2013 The future of work is here!\",\"subtitle\":\"Trusted by 1000+ Business\",\"feature_one\":\"100% Remote\",\"feature_two\":\"6700+ Jobs Available\",\"feature_three\":\"Great Job\",\"image\":\"67d92c14906c01742285844.png\",\"shape\":\"673af8b35ae361731918003.png\"}', NULL, 'basic', '', '2024-05-01 00:06:45', '2025-03-18 22:16:38'),
(65, 'faq.element', '{\"question\":\"What types of jobs are available on Olance?\",\"answer\":\"Olance offers a wide range of job categories, including writing, graphic design, web development, marketing, customer service, and many more, catering to various skill sets and industries.\"}', NULL, 'basic', '', '2024-05-04 00:21:20', '2025-03-15 22:03:13'),
(66, 'register_disable.content', '{\"has_image\":\"1\",\"heading\":\"Registration Currently Disabled\",\"subheading\":\"Page you are looking for doesn\'t exit or an other error occurred or temporarily unavailable.\",\"button_name\":\"Go to Home\",\"button_url\":\"#\",\"image\":\"663a0f20ecd0b1715080992.png\"}', NULL, 'basic', '', '2024-05-07 05:23:12', '2024-05-07 05:28:09'),
(67, 'client.element', '{\"has_image\":\"1\",\"image\":\"67d9402f2dd2c1742290991.png\"}', NULL, 'basic', '', '2024-11-18 02:33:32', '2025-03-18 03:43:11'),
(68, 'client.element', '{\"has_image\":\"1\",\"image\":\"67d94065e1acf1742291045.png\"}', NULL, 'basic', '', '2024-11-18 02:33:37', '2025-03-18 03:44:05'),
(69, 'client.element', '{\"has_image\":\"1\",\"image\":\"67d9405f5cbd31742291039.png\"}', NULL, 'basic', '', '2024-11-18 02:33:42', '2025-03-18 03:43:59'),
(70, 'client.element', '{\"has_image\":\"1\",\"image\":\"67d940590aaa81742291033.png\"}', NULL, 'basic', '', '2024-11-18 02:33:48', '2025-03-18 03:43:53'),
(71, 'client.element', '{\"has_image\":\"1\",\"image\":\"67d94051c71041742291025.png\"}', NULL, 'basic', '', '2024-11-18 02:33:53', '2025-03-18 03:43:45'),
(72, 'client.element', '{\"has_image\":\"1\",\"image\":\"67d94047733c51742291015.png\"}', NULL, 'basic', '', '2024-11-18 02:34:01', '2025-03-18 03:43:35'),
(73, 'how_work.content', '{\"heading\":\"It\'s Easy to Get Work Done\",\"subheading\":\"Our platform connects clients with freelancers to get work done efficiently and securely.\"}', NULL, 'basic', '', '2024-11-18 04:07:17', '2025-03-18 03:49:00'),
(74, 'how_work.element', '{\"icon\":\"<i class=\\\"fas fa-briefcase\\\"><\\/i>\",\"title\":\"Post a Job\",\"content\":\"Posting a job is easy. Provide a detailed description of your project, set your budget, and specify any requirements.\"}', NULL, 'basic', '', '2024-11-18 04:07:50', '2025-03-18 01:29:44'),
(75, 'how_work.element', '{\"icon\":\"<i class=\\\"fas fa-user-graduate\\\"><\\/i>\",\"title\":\"Hire Freelancers\",\"content\":\"Browse through our extensive list of freelancers, review their profiles, and find the perfect match for your project.\"}', NULL, 'basic', '', '2024-11-18 04:08:15', '2025-03-10 23:16:12'),
(76, 'how_work.element', '{\"icon\":\"<i class=\\\"fas fa-check-square\\\"><\\/i>\",\"title\":\"Get Work Done\",\"content\":\"Once you\'ve hired, directly communicate through our platform to discuss project details and milestones.\"}', NULL, 'basic', '', '2024-11-18 04:08:36', '2025-03-18 01:29:35'),
(77, 'how_work.element', '{\"icon\":\"<i class=\\\"fas fa-hand-holding-usd\\\"><\\/i>\",\"title\":\"Make Secure Payments\",\"content\":\"We ensure that your transactions are safe and secure. Use our platform to make payments with confidence.\"}', NULL, 'basic', '', '2024-11-18 04:08:56', '2025-03-10 23:14:23'),
(78, 'account.content', '{\"has_image\":\"1\",\"freelancer_title\":\"Sign Up as a Freelancer\",\"freelancer_content\":\"Showcase your skills, connect with buyers, and get hired.\",\"freelancer_button_name\":\"Create Freelance Account\",\"buyer_title\":\"Sign Up as a Buyer\",\"buyer_content\":\"Post jobs, hire skilled talent, and get projects done.\",\"buyer_button_name\":\"Create Buyer Account\",\"freelancer\":\"67d929f13124f1742285297.png\",\"buyer\":\"67d929f13efd31742285297.png\"}', NULL, 'basic', '', '2024-11-18 05:04:43', '2025-03-18 02:08:17'),
(79, 'why_choose.content', '{\"heading\":\"Why You Should Choose Us\",\"subheading\":\"Discover the benefits of using our platform for your freelancing and hiring needs.\"}', NULL, 'basic', '', '2024-11-18 05:20:41', '2025-03-18 03:49:48'),
(80, 'why_choose.element', '{\"has_image\":\"1\",\"title\":\"Proof & Quality\",\"content\":\"We ensure high-quality results with our proof of work and milestone system. Review completed work before releasing payments.\",\"image\":\"67d92d4630f5f1742286150.png\"}', NULL, 'basic', '', '2024-11-18 05:21:04', '2025-03-18 03:51:18'),
(81, 'why_choose.element', '{\"has_image\":\"1\",\"title\":\"No Cost Until You Hire\",\"content\":\"Enjoy our platform with zero upfront costs. You only pay when you hire a freelancer and paid if hastle free done your project.\",\"image\":\"67d92d3aea8ba1742286138.png\"}', NULL, 'basic', '', '2024-11-18 05:21:44', '2025-03-18 03:51:43'),
(82, 'why_choose.element', '{\"has_image\":\"1\",\"title\":\"Safe and Secure\",\"content\":\"Our platform uses advanced security measures to protect your data and transactions, ensuring a secure experience.\",\"image\":\"67d92d322b52c1742286130.png\"}', NULL, 'basic', '', '2024-11-18 05:22:29', '2025-03-18 03:52:43'),
(83, 'why_choose.element', '{\"has_image\":\"1\",\"title\":\"Post Job & Hire a Pro\",\"content\":\"Clients can easily post job and hire professionals. Provide detailed project requirements and attract proposals from qualified freelancers.\",\"image\":\"67d92d2981d5d1742286121.png\"}', NULL, 'basic', '', '2024-11-18 05:22:52', '2025-03-18 03:52:29'),
(84, 'why_choose.element', '{\"has_image\":\"1\",\"title\":\"Bid to Find Jobs\",\"content\":\"Freelancers can bid on jobs. Showcase your skills, submit proposals, and secure projects that match your expertise.\",\"image\":\"67d92d210a8a91742286113.png\"}', NULL, 'basic', '', '2024-11-18 05:23:10', '2025-03-18 03:52:11'),
(85, 'why_choose.element', '{\"has_image\":\"1\",\"title\":\"Top Rated\",\"content\":\"We host top-rated freelancers who are experts in their fields. Browse profiles & review ratings to find the best talents.\",\"image\":\"67d92d169e8d61742286102.png\"}', NULL, 'basic', '', '2024-11-18 05:23:29', '2025-03-18 02:21:42'),
(86, 'find_task.content', '{\"has_image\":\"1\",\"subtitle\":\"Find Your Task\",\"heading\":\"Find your work as your skill\",\"subheading\":\"Unlock your potential and find work that matches your skills and expertise. Our platform is designed to connect talented freelancers with clients who need their services.\",\"button_name\":\"Find Your Work\",\"image\":\"67d930681c42a1742286952.png\",\"shape\":\"673b2b970126d1731931031.png\"}', NULL, 'basic', '', '2024-11-18 05:57:10', '2025-03-18 02:35:52'),
(87, 'find_task.element', '{\"find_step\":\"Access expert talent to fill your skill gaps\"}', NULL, 'basic', '', '2024-11-18 05:57:17', '2024-11-18 05:57:17'),
(88, 'find_task.element', '{\"find_step\":\"Control your workflow : bid & proved your skill\"}', NULL, 'basic', '', '2024-11-18 05:57:22', '2024-11-18 05:57:22'),
(89, 'find_task.element', '{\"find_step\":\"Always grow your skill & find job\"}', NULL, 'basic', '', '2024-11-18 05:57:28', '2024-11-18 05:57:28'),
(90, 'facility.content', '{\"has_image\":\"1\",\"heading\":\"How\'s Olance is Different\",\"subheading\":\"Discover the facilities, or benefits of using OLACE for your freelancing and hiring needs.\",\"image\":\"67d9265b17fcb1742284379.png\"}', NULL, 'basic', '', '2024-11-18 06:19:06', '2025-03-18 03:55:46'),
(91, 'facility.element', '{\"title\":\"Higher Quality Listings\",\"content\":\"We ensure that our job listings are of the highest quality. Each listing is thoroughly vetted to ensure it meets our standards, providing you with the best opportunities to showcase your skills and expertise.\"}', NULL, 'basic', '', '2024-11-18 06:20:21', '2025-03-10 23:39:09'),
(92, 'facility.element', '{\"title\":\"Unlimited Job Search Resources\",\"content\":\"With Olance, you access unlimited job search resources. Use advanced filters, personalized job recommendations, and comprehensive listings to find the best match for your skills & goals.\"}', NULL, 'basic', '', '2024-11-18 06:20:28', '2025-03-18 03:59:04'),
(93, 'facility.element', '{\"title\":\"Save Time\",\"content\":\"We ensure that our job listings are of the highest quality. Each listing is thoroughly vetted to ensure it meets our standards, providing you with the best opportunities to showcase your skills and expertise.\"}', NULL, 'basic', '', '2024-11-18 06:20:38', '2025-03-10 23:39:26'),
(94, 'completion_work.content', '{\"has_image\":\"1\",\"heading\":\"Greeting\",\"subheading\":\"Finishing Work Has Never Been More Straightforward\",\"image\":\"67d925f7de2fb1742284279.png\"}', NULL, 'basic', '', '2024-11-18 06:31:56', '2025-03-18 03:56:29'),
(95, 'completion_work.element', '{\"done_step\":\"Get matched with expert freelancers in minutes\"}', NULL, 'basic', '', '2024-11-18 06:32:05', '2024-11-18 06:32:05'),
(96, 'completion_work.element', '{\"done_step\":\"Dedicated 24\\/7 customer service team\"}', NULL, 'basic', '', '2024-11-18 06:32:10', '2024-11-18 06:32:10'),
(97, 'completion_work.element', '{\"done_step\":\"Money back guarantee and anti-fraud protection\"}', NULL, 'basic', '', '2024-11-18 06:32:15', '2024-11-18 06:32:15'),
(98, 'testimonial.content', '{\"heading\":\"Our Users Feedback\",\"subheading\":\"Hear what our users have to say about their experience with our platform.\"}', NULL, 'basic', '', '2024-11-18 06:55:57', '2025-03-10 23:44:39'),
(99, 'testimonial.element', '{\"has_image\":\"1\",\"quote\":\"I\'ve saved so much time using this platform. The advanced search filters and personalized job recommendations are fantastic.\",\"name\":\"John Smith\",\"country\":\"UK\",\"image\":\"67d9338740e281742287751.png\"}', NULL, 'basic', '', '2024-11-18 06:56:24', '2025-03-18 02:49:11'),
(100, 'testimonial.element', '{\"has_image\":\"1\",\"quote\":\"The platform is incredibly easy to use. Posting jobs, communicating with freelancers, and managing projects has never been simpler.\",\"name\":\"Sarah Johnson\",\"country\":\"USA\",\"image\":\"67d9337f5f5951742287743.png\"}', NULL, 'basic', '', '2024-11-18 06:56:51', '2025-03-18 02:49:03'),
(101, 'testimonial.element', '{\"has_image\":\"1\",\"quote\":\"The quality of freelancers on this platform is exceptional. I\'ve hired top-rated professionals who delivered outstanding results.\",\"name\":\"Richitya Roy\",\"country\":\"India\",\"image\":\"67d9335d03c161742287709.png\"}', NULL, 'basic', '', '2024-11-18 06:57:35', '2025-03-18 02:48:29'),
(102, 'testimonial.element', '{\"has_image\":\"1\",\"quote\":\"I\'m impressed with the security measures in place. I always feel safe making transactions and collaborating with clients.\",\"name\":\"Quinn Elena\",\"country\":\"Canada\",\"image\":\"67d933753ae241742287733.png\"}', NULL, 'basic', '', '2024-11-18 06:58:03', '2025-03-18 02:48:53'),
(103, 'testimonial.element', '{\"has_image\":\"1\",\"quote\":\"Using this platform has been a game-changer for my freelancing career. I\'ve connected with amazing clients and worked on exciting projects\",\"name\":\"Emily Davis\",\"country\":\"German\",\"image\":\"67d9334a3c9131742287690.png\"}', NULL, 'basic', '', '2024-11-18 06:58:37', '2025-03-18 02:48:10'),
(104, 'top_freelancer.content', '{\"heading\":\"Top Rated Freelancers\",\"subheading\":\"Access top-rated professionals. Browse profiles, read reviews, and hire the best talent.\"}', NULL, 'basic', '', '2024-11-18 07:01:00', '2025-03-18 03:57:15'),
(105, 'counter.element', '{\"icon\":\"<i class=\\\"far fa-star\\\"><\\/i>\",\"digit\":\"96\",\"content\":\"Top Rated freelancers, covering 8,766 skills\"}', NULL, 'basic', '', '2024-11-18 07:11:18', '2024-11-19 00:01:37'),
(106, 'counter.element', '{\"icon\":\"<i class=\\\"fa-solid fa-sack-dollar\\\"><\\/i>\",\"digit\":\"110\",\"content\":\"Every year earned by top freelancers earning over $7,000\\/m\"}', NULL, 'basic', '', '2024-11-18 07:11:46', '2025-03-15 21:58:16'),
(107, 'counter.element', '{\"icon\":\"<i class=\\\"fas fa-hourglass-half\\\"><\\/i>\",\"digit\":\"3\",\"content\":\"Find task a freelancer, with 90% of projects completed in 7 days\"}', NULL, 'basic', '', '2024-11-18 07:13:11', '2024-11-19 00:02:21'),
(108, 'subscribe.content', '{\"has_image\":\"1\",\"heading\":\"Subscribe Our Newsletter\",\"subheading\":\"1000+ user subscribe our newsletter\",\"image\":\"67d9340c2ee7b1742287884.png\",\"shape\":\"673b4178ca7a71731936632.png\"}', NULL, 'basic', '', '2024-11-18 07:30:32', '2025-03-18 22:19:58'),
(109, 'blog.element', '{\"has_image\":[\"1\"],\"title\":\"Staying Inspired: Cultivating Creativity in Freelance Work\",\"description\":\"<div>The landscape of service center automation has undergone a remarkable transformation over the past few decades,\\r\\n    shifting from reactive to proactive approaches. Initially, service centers operated on a reactive model, primarily\\r\\n    addressing issues only after they arose. This method often involved responding to customer complaints and\\r\\n    troubleshooting problems as they emerged, leading to higher levels of downtime and customer dissatisfaction. The\\r\\n    focus was largely on fixing problems rather than preventing them, which created a cycle of continuous reaction to\\r\\n    service issues.<\\/div>\\r\\n<div><br \\/><\\/div>\\r\\n<h6 class=\\\"m-0\\\">Shifting Paradigms: The Move Toward Proactive Service Models<\\/h6>\\r\\n<div>As technology advanced, the paradigm began to shift towards a more proactive approach. The rise of advanced data\\r\\n    analytics and machine learning techniques enabled service centers to anticipate potential issues before they\\r\\n    occurred. By analyzing historical data and recognizing patterns, service centers could now predict when equipment\\r\\n    might fail or when a system could encounter problems. This shift not only reduced the frequency of unexpected\\r\\n    disruptions but also improved overall operational efficiency. Predictive maintenance became a key feature of\\r\\n    proactive service centers, allowing them to schedule maintenance activities during non-peak hours and address issues\\r\\n    before they impacted the customer experience.<\\/div>\\r\\n<div><br \\/><\\/div>\\r\\n<blockquote style=\\\"font-style:italic;text-align:center;padding:20px;color:#464545;background:rgba(139,131,133,0.54);font-size:18px;border-left:4px solid rgb(223,52,89);\\\">\\r\\n    As technology continues to evolve, service centers are likely to adopt even more advanced and innovative solutions that enhance their ability to provide seamless and proactive service.<\\/blockquote>\\r\\n<h6 class=\\\"m-0\\\">Harnessing Data Analytics and Machine Learning for Predictive Maintenance<\\/h6>\\r\\n<div>This evolution was further accelerated by the integration of artificial intelligence (AI) and automation tools.\\r\\n    AI-driven systems can now monitor and analyze real-time data from various sources, providing insights into potential\\r\\n    issues and suggesting preventive measures. Automation tools facilitate swift responses to identified issues, often\\r\\n    without the need for human intervention. For instance, automated systems can initiate repairs or adjustments based\\r\\n    on predefined parameters, ensuring that minor issues are resolved before they escalate into significant problems.\\r\\n<\\/div>\\r\\n<div><br \\/><\\/div>\\r\\n<h6 class=\\\"m-0\\\">The Role of AI and Automation in Revolutionizing Service Center Operations<\\/h6>\\r\\n<div>The proactive approach not only enhances operational efficiency but also fosters a more positive customer\\r\\n    experience. By addressing potential issues before they impact customers, service centers can ensure a higher level\\r\\n    of service continuity and reliability. Customers benefit from fewer disruptions and a more streamlined experience,\\r\\n    leading to increased satisfaction and loyalty.<\\/div>\\r\\n<div><br \\/><\\/div>\\r\\n<h6 class=\\\"m-0\\\">Enhancing Customer Experience Through Proactive Service Strategies<\\/h6>\\r\\n<div>In essence, the evolution from a reactive to a proactive model in service center automation represents a\\r\\n    significant leap forward. It highlights the importance of leveraging advanced technologies to not only address\\r\\n    problems but to anticipate and prevent them, ultimately leading to a more efficient and customer-centric operation.\\r\\n    As technology continues to evolve, service centers will likely see even more sophisticated solutions that further\\r\\n    enhance their ability to provide seamless and proactive service.<\\/div>\\r\\n<div><br \\/><\\/div>\",\"image\":\"67d290b4890d81741852852.png\"}', NULL, 'basic', 'staying-inspired-cultivating-creativity-in-freelance-work', '2024-11-18 23:00:53', '2025-03-19 01:59:04'),
(110, 'blog.element', '{\"has_image\":[\"1\"],\"title\":\"The Art of Self-Promotion: Marketing Strategies for Freelancers\",\"description\":\"<div>The landscape of service center automation has undergone a remarkable transformation over the past few decades,\\r\\n    shifting from reactive to proactive approaches. Initially, service centers operated on a reactive model, primarily\\r\\n    addressing issues only after they arose. This method often involved responding to customer complaints and\\r\\n    troubleshooting problems as they emerged, leading to higher levels of downtime and customer dissatisfaction. The\\r\\n    focus was largely on fixing problems rather than preventing them, which created a cycle of continuous reaction to\\r\\n    service issues.<\\/div>\\r\\n<div><br \\/><\\/div>\\r\\n<h6 class=\\\"m-0\\\">Shifting Paradigms: The Move Toward Proactive Service Models<\\/h6>\\r\\n<div>As technology advanced, the paradigm began to shift towards a more proactive approach. The rise of advanced data\\r\\n    analytics and machine learning techniques enabled service centers to anticipate potential issues before they\\r\\n    occurred. By analyzing historical data and recognizing patterns, service centers could now predict when equipment\\r\\n    might fail or when a system could encounter problems. This shift not only reduced the frequency of unexpected\\r\\n    disruptions but also improved overall operational efficiency. Predictive maintenance became a key feature of\\r\\n    proactive service centers, allowing them to schedule maintenance activities during non-peak hours and address issues\\r\\n    before they impacted the customer experience.<\\/div>\\r\\n<div><br \\/><\\/div>\\r\\n<blockquote style=\\\"font-style:italic;text-align:center;padding:20px;color:#464545;background:rgba(139,131,133,0.54);font-size:18px;border-left:4px solid rgb(223,52,89);\\\">\\r\\n    As technology continues to evolve, service centers are likely to adopt even more advanced and innovative solutions that enhance their ability to provide seamless and proactive service.<\\/blockquote>\\r\\n<h6 class=\\\"m-0\\\">Harnessing Data Analytics and Machine Learning for Predictive Maintenance<\\/h6>\\r\\n<div>This evolution was further accelerated by the integration of artificial intelligence (AI) and automation tools.\\r\\n    AI-driven systems can now monitor and analyze real-time data from various sources, providing insights into potential\\r\\n    issues and suggesting preventive measures. Automation tools facilitate swift responses to identified issues, often\\r\\n    without the need for human intervention. For instance, automated systems can initiate repairs or adjustments based\\r\\n    on predefined parameters, ensuring that minor issues are resolved before they escalate into significant problems.\\r\\n<\\/div>\\r\\n<div><br \\/><\\/div>\\r\\n<h6 class=\\\"m-0\\\">The Role of AI and Automation in Revolutionizing Service Center Operations<\\/h6>\\r\\n<div>The proactive approach not only enhances operational efficiency but also fosters a more positive customer\\r\\n    experience. By addressing potential issues before they impact customers, service centers can ensure a higher level\\r\\n    of service continuity and reliability. Customers benefit from fewer disruptions and a more streamlined experience,\\r\\n    leading to increased satisfaction and loyalty.<\\/div>\\r\\n<div><br \\/><\\/div>\\r\\n<h6 class=\\\"m-0\\\">Enhancing Customer Experience Through Proactive Service Strategies<\\/h6>\\r\\n<div>In essence, the evolution from a reactive to a proactive model in service center automation represents a\\r\\n    significant leap forward. It highlights the importance of leveraging advanced technologies to not only address\\r\\n    problems but to anticipate and prevent them, ultimately leading to a more efficient and customer-centric operation.\\r\\n    As technology continues to evolve, service centers will likely see even more sophisticated solutions that further\\r\\n    enhance their ability to provide seamless and proactive service.<\\/div>\\r\\n<div><br \\/><\\/div>\",\"image\":\"67d290a7d1e161741852839.png\"}', NULL, 'basic', 'the-art-of-self-promotion-marketing-strategies-for-freelancers', '2024-11-18 23:02:32', '2025-03-19 01:58:57'),
(111, 'blog.element', '{\"has_image\":[\"1\"],\"title\":\"Unlocking Your Full Potential: Strategies to Boost Freelance Productivity\",\"description\":\"<div>The landscape of service center automation has undergone a remarkable transformation over the past few decades,\\r\\n    shifting from reactive to proactive approaches. Initially, service centers operated on a reactive model, primarily\\r\\n    addressing issues only after they arose. This method often involved responding to customer complaints and\\r\\n    troubleshooting problems as they emerged, leading to higher levels of downtime and customer dissatisfaction. The\\r\\n    focus was largely on fixing problems rather than preventing them, which created a cycle of continuous reaction to\\r\\n    service issues.<\\/div>\\r\\n<div><br \\/><\\/div>\\r\\n<h6 class=\\\"m-0\\\">Shifting Paradigms: The Move Toward Proactive Service Models<\\/h6>\\r\\n<div>As technology advanced, the paradigm began to shift towards a more proactive approach. The rise of advanced data\\r\\n    analytics and machine learning techniques enabled service centers to anticipate potential issues before they\\r\\n    occurred. By analyzing historical data and recognizing patterns, service centers could now predict when equipment\\r\\n    might fail or when a system could encounter problems. This shift not only reduced the frequency of unexpected\\r\\n    disruptions but also improved overall operational efficiency. Predictive maintenance became a key feature of\\r\\n    proactive service centers, allowing them to schedule maintenance activities during non-peak hours and address issues\\r\\n    before they impacted the customer experience.<\\/div>\\r\\n<div><br \\/><\\/div>\\r\\n<blockquote style=\\\"font-style:italic;text-align:center;padding:20px;color:#464545;background:rgba(139,131,133,0.54);font-size:18px;border-left:4px solid rgb(223,52,89);\\\">\\r\\n    As technology continues to evolve, service centers are likely to adopt even more advanced and innovative solutions that enhance their ability to provide seamless and proactive service.<\\/blockquote>\\r\\n<h6 class=\\\"m-0\\\">Harnessing Data Analytics and Machine Learning for Predictive Maintenance<\\/h6>\\r\\n<div>This evolution was further accelerated by the integration of artificial intelligence (AI) and automation tools.\\r\\n    AI-driven systems can now monitor and analyze real-time data from various sources, providing insights into potential\\r\\n    issues and suggesting preventive measures. Automation tools facilitate swift responses to identified issues, often\\r\\n    without the need for human intervention. For instance, automated systems can initiate repairs or adjustments based\\r\\n    on predefined parameters, ensuring that minor issues are resolved before they escalate into significant problems.\\r\\n<\\/div>\\r\\n<div><br \\/><\\/div>\\r\\n<h6 class=\\\"m-0\\\">The Role of AI and Automation in Revolutionizing Service Center Operations<\\/h6>\\r\\n<div>The proactive approach not only enhances operational efficiency but also fosters a more positive customer\\r\\n    experience. By addressing potential issues before they impact customers, service centers can ensure a higher level\\r\\n    of service continuity and reliability. Customers benefit from fewer disruptions and a more streamlined experience,\\r\\n    leading to increased satisfaction and loyalty.<\\/div>\\r\\n<div><br \\/><\\/div>\\r\\n<h6 class=\\\"m-0\\\">Enhancing Customer Experience Through Proactive Service Strategies<\\/h6>\\r\\n<div>In essence, the evolution from a reactive to a proactive model in service center automation represents a\\r\\n    significant leap forward. It highlights the importance of leveraging advanced technologies to not only address\\r\\n    problems but to anticipate and prevent them, ultimately leading to a more efficient and customer-centric operation.\\r\\n    As technology continues to evolve, service centers will likely see even more sophisticated solutions that further\\r\\n    enhance their ability to provide seamless and proactive service.<\\/div>\\r\\n<div><br \\/><\\/div>\",\"image\":\"67d2909ceb7c01741852828.png\"}', NULL, 'basic', 'unlocking-your-full-potential-strategies-to-boost-freelance-productivity', '2024-11-18 23:03:03', '2025-03-19 01:58:49'),
(112, 'faq.content', '{\"heading\":\"Frequently Asked Questions\",\"subheading\":\"Find clear answers to the most common questions about our services, process, and support.\",\"has_image\":\"1\",\"image\":\"67d9248a6a2f51742283914.png\"}', NULL, 'basic', '', '2024-11-18 23:06:22', '2025-03-18 04:01:56'),
(113, 'faq.element', '{\"question\":\"What should I do if I encounter a difficult client?\",\"answer\":\"If you face challenges with a client, communicate openly to resolve issues, set clear expectations, and if necessary, consider ending the contract professionally if the situation does not improve.\"}', NULL, 'basic', '', '2024-11-18 23:07:44', '2025-03-15 22:01:01'),
(114, 'faq.element', '{\"question\":\"How can I increase my chances of getting hired?\",\"answer\":\"To increase your chances, maintain a complete and professional profile, submit tailored proposals for each job, and gather positive reviews from clients to build your reputation.\"}', NULL, 'basic', '', '2024-11-18 23:08:08', '2025-03-15 22:00:31'),
(115, 'faq.element', '{\"question\":\"How to bid for find work?\",\"answer\":\"Look for projects that match your skills and interests, consider the client\'s budget and feedback history, and assess the number of proposals already submitted to gauge competition.\"}', NULL, 'basic', '', '2024-11-18 23:08:29', '2025-03-15 21:59:59'),
(116, 'faq.element', '{\"question\":\"What is the best way to write a proposal?\",\"answer\":\"A strong proposal should address the client\'s needs, highlight your relevant experience, include specific examples of past work, and demonstrate your understanding of the project.\"}', NULL, 'basic', '', '2024-11-18 23:08:49', '2025-03-15 22:00:13'),
(117, 'social_icon.element', '{\"title\":\"X\",\"social_icon\":\"<i class=\\\"fa-brands fa-x-twitter\\\"><\\/i>\",\"url\":\"https:\\/\\/www.twitter.com\\/\"}', NULL, 'basic', '', '2024-11-18 23:33:12', '2024-11-18 23:33:12'),
(118, 'social_icon.element', '{\"title\":\"Linkedin\",\"social_icon\":\"<i class=\\\"fab fa-linkedin-in\\\"><\\/i>\",\"url\":\"https:\\/\\/www.linkedin.com\\/\"}', NULL, 'basic', '', '2024-11-18 23:34:29', '2024-11-18 23:34:29'),
(119, 'social_icon.element', '{\"title\":\"Instagram\",\"social_icon\":\"<i class=\\\"fab fa-instagram\\\"><\\/i>\",\"url\":\"https:\\/\\/www.instagram.com\\/\"}', NULL, 'basic', '', '2024-11-18 23:35:10', '2024-11-18 23:35:10');
INSERT INTO `frontends` (`id`, `data_keys`, `data_values`, `seo_content`, `tempname`, `slug`, `created_at`, `updated_at`) VALUES
(120, 'about.element', '{\"has_image\":\"1\",\"title\":\"Find a Freelancer and Hire Top Talent\",\"content\":\"To find top talent for your Upwork project, explore the platform\'s extensive freelancer database.\",\"image\":\"67d945cd160991742292429.png\"}', NULL, 'basic', '', '2024-11-19 00:39:29', '2025-03-18 22:24:14'),
(121, 'about.element', '{\"has_image\":\"1\",\"title\":\"Find a Job and Top Matches Buyer\",\"content\":\"Discover the perfect job opportunities on Upwork tailored to your skills.\",\"image\":\"67d945b86adf21742292408.png\"}', NULL, 'basic', '', '2024-11-19 00:41:20', '2025-03-18 22:25:19'),
(122, 'about.element', '{\"has_image\":\"1\",\"title\":\"Work without breaking the bank\",\"content\":\"Maximize your budget by leveraging Upwork\\u2019s diverse talent pool.\",\"image\":\"67d945a395d981742292387.png\"}', NULL, 'basic', '', '2024-11-19 00:42:08', '2025-03-18 04:06:27'),
(123, 'client.element', '{\"has_image\":\"1\",\"image\":\"67d9404143eb31742291009.png\"}', NULL, 'basic', '', '2024-11-19 00:52:59', '2025-03-18 03:43:29'),
(125, 'brand.content', '{\"heading\":\"Trusted by 100,000+ Business\"}', NULL, 'basic', '', '2024-11-19 01:02:27', '2024-11-19 01:02:27'),
(126, 'brand.element', '{\"has_image\":\"1\",\"image\":\"67d92ef3296931742286579.png\"}', NULL, 'basic', '', '2024-11-19 01:02:33', '2025-03-18 02:29:39'),
(127, 'brand.element', '{\"has_image\":\"1\",\"image\":\"673c3965223e71732000101.png\"}', NULL, 'basic', '', '2024-11-19 01:02:38', '2024-11-19 01:08:21'),
(128, 'brand.element', '{\"has_image\":\"1\",\"image\":\"67d92efee09041742286590.png\"}', NULL, 'basic', '', '2024-11-19 01:02:44', '2025-03-18 02:29:50'),
(129, 'brand.element', '{\"has_image\":\"1\",\"image\":\"67d92eeae2c5a1742286570.png\"}', NULL, 'basic', '', '2024-11-19 01:02:49', '2025-03-18 02:29:30'),
(130, 'brand.element', '{\"has_image\":\"1\",\"image\":\"67d941292ac081742291241.png\"}', NULL, 'basic', '', '2024-11-19 01:02:56', '2025-03-18 03:47:21'),
(131, 'brand.element', '{\"has_image\":\"1\",\"image\":\"67d92ed8792921742286552.png\"}', NULL, 'basic', '', '2024-11-19 01:03:06', '2025-03-18 02:29:12'),
(132, 'brand.element', '{\"has_image\":\"1\",\"image\":\"67d92ed2b46b31742286546.png\"}', NULL, 'basic', '', '2024-11-19 01:03:12', '2025-03-18 02:29:06'),
(133, 'brand.element', '{\"has_image\":\"1\",\"image\":\"67d92ecd88df01742286541.png\"}', NULL, 'basic', '', '2024-11-19 01:03:18', '2025-03-18 02:29:01'),
(134, 'support.content', '{\"has_image\":\"1\",\"heading\":\"24\\/7 customer support\",\"hotline_number\":\"5896\",\"hotline_email\":\"support@gmail.com\",\"image\":\"67d92e88c6abb1742286472.png\",\"shape\":\"67d929feeafa01742285310.png\"}', NULL, 'basic', '', '2024-11-19 01:05:27', '2025-03-18 02:27:52'),
(135, 'brand.element', '{\"has_image\":\"1\",\"image\":\"67d92ec84780c1742286536.png\"}', NULL, 'basic', '', '2024-11-19 01:06:37', '2025-03-18 02:28:56'),
(136, 'login.content', '{\"has_image\":\"1\",\"heading\":\"Login Account\",\"image\":\"673c528892dfc1732006536.png\"}', NULL, 'basic', '', '2024-11-19 02:54:00', '2024-12-23 23:59:07'),
(137, 'register.content', '{\"has_image\":\"1\",\"heading\":\"Register Account\",\"image\":\"673c52aeac7991732006574.png\"}', NULL, 'basic', '', '2024-11-19 02:56:14', '2024-12-23 23:59:18'),
(138, 'banned.content', '{\"has_image\":\"1\",\"heading\":\"You are banned\",\"image\":\"673c88ad289b31732020397.png\"}', NULL, 'basic', '', '2024-11-19 06:46:37', '2024-11-19 06:46:38'),
(139, 'blog.element', '{\"has_image\":[\"1\"],\"title\":\"Do professional logo design, unlimited revisions, favicon and artwork\",\"description\":\"<div>The landscape of service center automation has undergone a remarkable transformation over the past few decades,\\r\\n    shifting from reactive to proactive approaches. Initially, service centers operated on a reactive model, primarily\\r\\n    addressing issues only after they arose. This method often involved responding to customer complaints and\\r\\n    troubleshooting problems as they emerged, leading to higher levels of downtime and customer dissatisfaction. The\\r\\n    focus was largely on fixing problems rather than preventing them, which created a cycle of continuous reaction to\\r\\n    service issues.<\\/div>\\r\\n<div><br \\/><\\/div>\\r\\n<h6 class=\\\"m-0\\\">Shifting Paradigms: The Move Toward Proactive Service Models<\\/h6>\\r\\n<div>As technology advanced, the paradigm began to shift towards a more proactive approach. The rise of advanced data\\r\\n    analytics and machine learning techniques enabled service centers to anticipate potential issues before they\\r\\n    occurred. By analyzing historical data and recognizing patterns, service centers could now predict when equipment\\r\\n    might fail or when a system could encounter problems. This shift not only reduced the frequency of unexpected\\r\\n    disruptions but also improved overall operational efficiency. Predictive maintenance became a key feature of\\r\\n    proactive service centers, allowing them to schedule maintenance activities during non-peak hours and address issues\\r\\n    before they impacted the customer experience.<\\/div>\\r\\n<div><br \\/><\\/div>\\r\\n<blockquote style=\\\"font-style:italic;text-align:center;padding:20px;color:#464545;background:rgba(139,131,133,0.54);font-size:18px;border-left:4px solid rgb(223,52,89);\\\">\\r\\n    As technology continues to evolve, service centers are likely to adopt even more advanced and innovative solutions that enhance their ability to provide seamless and proactive service.<\\/blockquote>\\r\\n<h6 class=\\\"m-0\\\">Harnessing Data Analytics and Machine Learning for Predictive Maintenance<\\/h6>\\r\\n<div>This evolution was further accelerated by the integration of artificial intelligence (AI) and automation tools.\\r\\n    AI-driven systems can now monitor and analyze real-time data from various sources, providing insights into potential\\r\\n    issues and suggesting preventive measures. Automation tools facilitate swift responses to identified issues, often\\r\\n    without the need for human intervention. For instance, automated systems can initiate repairs or adjustments based\\r\\n    on predefined parameters, ensuring that minor issues are resolved before they escalate into significant problems.\\r\\n<\\/div>\\r\\n<div><br \\/><\\/div>\\r\\n<h6 class=\\\"m-0\\\">The Role of AI and Automation in Revolutionizing Service Center Operations<\\/h6>\\r\\n<div>The proactive approach not only enhances operational efficiency but also fosters a more positive customer\\r\\n    experience. By addressing potential issues before they impact customers, service centers can ensure a higher level\\r\\n    of service continuity and reliability. Customers benefit from fewer disruptions and a more streamlined experience,\\r\\n    leading to increased satisfaction and loyalty.<\\/div>\\r\\n<div><br \\/><\\/div>\\r\\n<h6 class=\\\"m-0\\\">Enhancing Customer Experience Through Proactive Service Strategies<\\/h6>\\r\\n<div>In essence, the evolution from a reactive to a proactive model in service center automation represents a\\r\\n    significant leap forward. It highlights the importance of leveraging advanced technologies to not only address\\r\\n    problems but to anticipate and prevent them, ultimately leading to a more efficient and customer-centric operation.\\r\\n    As technology continues to evolve, service centers will likely see even more sophisticated solutions that further\\r\\n    enhance their ability to provide seamless and proactive service.<\\/div>\\r\\n<div><br \\/><\\/div>\",\"image\":\"67d2909041f941741852816.png\"}', NULL, 'basic', 'do-professional-logo-design-unlimited-revisions-favicon-and-artwork', '2025-01-07 05:46:05', '2025-03-19 01:58:28'),
(140, 'blog.element', '{\"has_image\":[\"1\"],\"title\":\"The ultimate guide to creating a Wix website\",\"description\":\"<div>The landscape of service center automation has undergone a remarkable transformation over the past few decades,\\r\\n    shifting from reactive to proactive approaches. Initially, service centers operated on a reactive model, primarily\\r\\n    addressing issues only after they arose. This method often involved responding to customer complaints and\\r\\n    troubleshooting problems as they emerged, leading to higher levels of downtime and customer dissatisfaction. The\\r\\n    focus was largely on fixing problems rather than preventing them, which created a cycle of continuous reaction to\\r\\n    service issues.<\\/div>\\r\\n<div><br \\/><\\/div>\\r\\n<h6 class=\\\"m-0\\\">Shifting Paradigms: The Move Toward Proactive Service Models<\\/h6>\\r\\n<div>As technology advanced, the paradigm began to shift towards a more proactive approach. The rise of advanced data\\r\\n    analytics and machine learning techniques enabled service centers to anticipate potential issues before they\\r\\n    occurred. By analyzing historical data and recognizing patterns, service centers could now predict when equipment\\r\\n    might fail or when a system could encounter problems. This shift not only reduced the frequency of unexpected\\r\\n    disruptions but also improved overall operational efficiency. Predictive maintenance became a key feature of\\r\\n    proactive service centers, allowing them to schedule maintenance activities during non-peak hours and address issues\\r\\n    before they impacted the customer experience.<\\/div>\\r\\n<div><br \\/><\\/div>\\r\\n<blockquote style=\\\"font-style:italic;text-align:center;padding:20px;color:#464545;background:rgba(139,131,133,0.54);font-size:18px;border-left:4px solid rgb(223,52,89);\\\">\\r\\n    As technology continues to evolve, service centers are likely to adopt even more advanced and innovative solutions that enhance their ability to provide seamless and proactive service.<\\/blockquote>\\r\\n<h6 class=\\\"m-0\\\">Harnessing Data Analytics and Machine Learning for Predictive Maintenance<\\/h6>\\r\\n<div>This evolution was further accelerated by the integration of artificial intelligence (AI) and automation tools.\\r\\n    AI-driven systems can now monitor and analyze real-time data from various sources, providing insights into potential\\r\\n    issues and suggesting preventive measures. Automation tools facilitate swift responses to identified issues, often\\r\\n    without the need for human intervention. For instance, automated systems can initiate repairs or adjustments based\\r\\n    on predefined parameters, ensuring that minor issues are resolved before they escalate into significant problems.\\r\\n<\\/div>\\r\\n<div><br \\/><\\/div>\\r\\n<h6 class=\\\"m-0\\\">The Role of AI and Automation in Revolutionizing Service Center Operations<\\/h6>\\r\\n<div>The proactive approach not only enhances operational efficiency but also fosters a more positive customer\\r\\n    experience. By addressing potential issues before they impact customers, service centers can ensure a higher level\\r\\n    of service continuity and reliability. Customers benefit from fewer disruptions and a more streamlined experience,\\r\\n    leading to increased satisfaction and loyalty.<\\/div>\\r\\n<div><br \\/><\\/div>\\r\\n<h6 class=\\\"m-0\\\">Enhancing Customer Experience Through Proactive Service Strategies<\\/h6>\\r\\n<div>In essence, the evolution from a reactive to a proactive model in service center automation represents a\\r\\n    significant leap forward. It highlights the importance of leveraging advanced technologies to not only address\\r\\n    problems but to anticipate and prevent them, ultimately leading to a more efficient and customer-centric operation.\\r\\n    As technology continues to evolve, service centers will likely see even more sophisticated solutions that further\\r\\n    enhance their ability to provide seamless and proactive service.<\\/div>\\r\\n<div><br \\/><\\/div>\",\"image\":\"67d2908470ce21741852804.png\"}', NULL, 'basic', 'the-ultimate-guide-to-creating-a-wix-website', '2025-01-07 05:48:41', '2025-03-19 01:39:13'),
(141, 'client.element', '{\"has_image\":\"1\",\"image\":\"67d9401a1a1b21742290970.png\"}', NULL, 'basic', '', '2025-01-26 07:35:50', '2025-03-18 03:42:50'),
(142, 'client.element', '{\"has_image\":\"1\",\"image\":\"67d9401033c001742290960.png\"}', NULL, 'basic', '', '2025-01-26 07:35:54', '2025-03-18 03:42:40'),
(143, 'client.element', '{\"has_image\":\"1\",\"image\":\"67d940083980d1742290952.png\"}', NULL, 'basic', '', '2025-01-26 07:35:59', '2025-03-18 03:42:33'),
(144, 'switching_button.content', '{\"freelancer_login_button\":\"Login as Freelancer\",\"buyer_login_button\":\"Login as Buyer\",\"freelancer_register_button\":\"Join as Freelancer\",\"buyer_register_button\":\"Join as Buyer\"}', NULL, 'basic', '', '2025-03-05 01:16:22', '2025-03-05 01:16:22'),
(145, 'policy_pages.element', '{\"title\":\"Bid Policy\",\"details\":\"<h5>Introduction<\\/h5>\\r\\n        <p>\\r\\n            This Privacy Policy describes how we collect, uses, and discloses information, including personal information, in connection with your use of our website.\\r\\n        <\\/p>\\r\\n        <br \\/>\\r\\n        <h5>Information We Collect<\\/h5>\\r\\n        <p>We collect two main types of information on the Website:<\\/p>\\r\\n        <ul>\\r\\n            <li><p><strong>Personal Information: <\\/strong>This includes data that can identify you as an individual, such as your name, email address, phone number, or mailing address. We only collect this information when you voluntarily provide it to us, like signing up for a newsletter, contacting us through a form, or making a purchase.<\\/p><\\/li>\\r\\n            <li><p><strong>Non-Personal Information: <\\/strong>This data cannot be used to identify you directly. It includes details like your browser type, device type, operating system, IP address, browsing activity, and usage statistics. We collect this information automatically through cookies and other tracking technologies.<\\/p><\\/li>\\r\\n        <\\/ul>\\r\\n        <br \\/>\\r\\n        <h5>How We Use Information<\\/h5>\\r\\n        <p>The information we collect allows us to:<\\/p>\\r\\n        <ul>\\r\\n            <li>Operate and maintain the Website effectively.<\\/li>\\r\\n            <li>Send you newsletters or marketing communications, but only with your consent.<\\/li>\\r\\n            <li>Respond to your inquiries and fulfill your requests.<\\/li>\\r\\n            <li>Improve the Website and your user experience.<\\/li>\\r\\n            <li>Personalize your experience on the Website based on your browsing habits.<\\/li>\\r\\n            <li>Analyze how the Website is used to improve our services.<\\/li>\\r\\n            <li>Comply with legal and regulatory requirements.<\\/li>\\r\\n        <\\/ul>\\r\\n        <br \\/>\\r\\n        <h5>Sharing of Information<\\/h5>\\r\\n        <p>We may share your information with trusted third-party service providers who assist us in operating the Website and delivering our services. These providers are obligated by contract to keep your information confidential and use it only for the specific purposes we disclose it for.<\\/p>\\r\\n        <p>We will never share your personal information with any third parties for marketing purposes without your explicit consent.<\\/p>\\r\\n        <br \\/>\\r\\n        <h5>Data Retention<\\/h5>\\r\\n        <p>We retain your personal information only for as long as necessary to fulfill the purposes it was collected for. We may retain it for longer periods only if required or permitted by law.<\\/p>\\r\\n        <br \\/>\\r\\n        <h5>Security Measures<\\/h5>\\r\\n        <p>We take reasonable precautions to protect your information from unauthorized access, disclosure, alteration, or destruction. However, complete security cannot be guaranteed for any website or internet transmission.<\\/p>\\r\\n        <br \\/>\\r\\n        <h5>Changes to this Privacy Policy<\\/h5>\\r\\n        <p>We may update this Privacy Policy periodically. We will notify you of any changes by posting the revised policy on the Website. We recommend reviewing this policy regularly to stay informed of any updates.<\\/p>\\r\\n        <p><strong>Remember:<\\/strong>  This is a sample policy and may need adjustments to comply with specific laws and reflect your website\'s unique data practices. Consider consulting with a legal professional to ensure your policy is fully compliant.<\\/p>\"}', NULL, 'basic', 'bid-policy', '2025-03-11 04:27:13', '2025-03-16 22:00:04'),
(146, 'breadcrumb.content', '{\"has_image\":\"1\",\"image\":\"67d2a9cd026fd1741859277.png\"}', NULL, 'basic', '', '2025-03-13 03:47:56', '2025-03-13 03:47:57'),
(147, 'faq.element', '{\"question\":\"How can I improve my Upwork profile visibility?\",\"answer\":\"To enhance your profile visibility, optimize your profile with relevant keywords, maintain a high job success score, gather positive client feedback, and actively apply for jobs that match your skills.\"}', NULL, 'basic', '', '2025-03-15 22:01:29', '2025-03-15 22:01:29');

-- --------------------------------------------------------

--
-- Table structure for table `gateways`
--

CREATE TABLE `gateways` (
  `id` bigint UNSIGNED NOT NULL,
  `form_id` int UNSIGNED NOT NULL DEFAULT '0',
  `code` int DEFAULT NULL,
  `name` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alias` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'NULL',
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1=>enable, 2=>disable',
  `gateway_parameters` text COLLATE utf8mb4_unicode_ci,
  `supported_currencies` text COLLATE utf8mb4_unicode_ci,
  `crypto` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0: fiat currency, 1: crypto currency',
  `extra` text COLLATE utf8mb4_unicode_ci,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gateways`
--

INSERT INTO `gateways` (`id`, `form_id`, `code`, `name`, `alias`, `image`, `status`, `gateway_parameters`, `supported_currencies`, `crypto`, `extra`, `description`, `created_at`, `updated_at`) VALUES
(1, 0, 101, 'Paypal', 'Paypal', '663a38d7b455d1715091671.png', 1, '{\"paypal_email\":{\"title\":\"PayPal Email\",\"global\":true,\"value\":\"sb-owud61543012@business.example.com\"}}', '{\"AUD\":\"AUD\",\"BRL\":\"BRL\",\"CAD\":\"CAD\",\"CZK\":\"CZK\",\"DKK\":\"DKK\",\"EUR\":\"EUR\",\"HKD\":\"HKD\",\"HUF\":\"HUF\",\"INR\":\"INR\",\"ILS\":\"ILS\",\"JPY\":\"JPY\",\"MYR\":\"MYR\",\"MXN\":\"MXN\",\"TWD\":\"TWD\",\"NZD\":\"NZD\",\"NOK\":\"NOK\",\"PHP\":\"PHP\",\"PLN\":\"PLN\",\"GBP\":\"GBP\",\"RUB\":\"RUB\",\"SGD\":\"SGD\",\"SEK\":\"SEK\",\"CHF\":\"CHF\",\"THB\":\"THB\",\"USD\":\"$\"}', 0, NULL, NULL, '2019-09-14 13:14:22', '2024-05-07 08:21:11'),
(2, 0, 102, 'Perfect Money', 'PerfectMoney', '663a3920e30a31715091744.png', 1, '{\"passphrase\":{\"title\":\"ALTERNATE PASSPHRASE\",\"global\":true,\"value\":\"hR26aw02Q1eEeUPSIfuwNypXX\"},\"wallet_id\":{\"title\":\"PM Wallet\",\"global\":false,\"value\":\"\"}}', '{\"USD\":\"$\",\"EUR\":\"\\u20ac\"}', 0, NULL, NULL, '2019-09-14 13:14:22', '2024-05-07 08:22:24'),
(3, 0, 103, 'Stripe Hosted', 'Stripe', '663a39861cb9d1715091846.png', 1, '{\"secret_key\":{\"title\":\"Secret Key\",\"global\":true,\"value\":\"sk_test_51I6GGiCGv1sRiQlEi5v1or9eR0HVbuzdMd2rW4n3DxC8UKfz66R4X6n4yYkzvI2LeAIuRU9H99ZpY7XCNFC9xMs500vBjZGkKG\"},\"publishable_key\":{\"title\":\"PUBLISHABLE KEY\",\"global\":true,\"value\":\"pk_test_51I6GGiCGv1sRiQlEOisPKrjBqQqqcFsw8mXNaZ2H2baN6R01NulFS7dKFji1NRRxuchoUTEDdB7ujKcyKYSVc0z500eth7otOM\"}}', '{\"USD\":\"USD\",\"AUD\":\"AUD\",\"BRL\":\"BRL\",\"CAD\":\"CAD\",\"CHF\":\"CHF\",\"DKK\":\"DKK\",\"EUR\":\"EUR\",\"GBP\":\"GBP\",\"HKD\":\"HKD\",\"INR\":\"INR\",\"JPY\":\"JPY\",\"MXN\":\"MXN\",\"MYR\":\"MYR\",\"NOK\":\"NOK\",\"NZD\":\"NZD\",\"PLN\":\"PLN\",\"SEK\":\"SEK\",\"SGD\":\"SGD\"}', 0, NULL, NULL, '2019-09-14 13:14:22', '2025-03-10 00:30:29'),
(4, 0, 104, 'Skrill', 'Skrill', '663a39494c4a91715091785.png', 1, '{\"pay_to_email\":{\"title\":\"Skrill Email\",\"global\":true,\"value\":\"merchant@skrill.com\"},\"secret_key\":{\"title\":\"Secret Key\",\"global\":true,\"value\":\"---\"}}', '{\"AED\":\"AED\",\"AUD\":\"AUD\",\"BGN\":\"BGN\",\"BHD\":\"BHD\",\"CAD\":\"CAD\",\"CHF\":\"CHF\",\"CZK\":\"CZK\",\"DKK\":\"DKK\",\"EUR\":\"EUR\",\"GBP\":\"GBP\",\"HKD\":\"HKD\",\"HRK\":\"HRK\",\"HUF\":\"HUF\",\"ILS\":\"ILS\",\"INR\":\"INR\",\"ISK\":\"ISK\",\"JOD\":\"JOD\",\"JPY\":\"JPY\",\"KRW\":\"KRW\",\"KWD\":\"KWD\",\"MAD\":\"MAD\",\"MYR\":\"MYR\",\"NOK\":\"NOK\",\"NZD\":\"NZD\",\"OMR\":\"OMR\",\"PLN\":\"PLN\",\"QAR\":\"QAR\",\"RON\":\"RON\",\"RSD\":\"RSD\",\"SAR\":\"SAR\",\"SEK\":\"SEK\",\"SGD\":\"SGD\",\"THB\":\"THB\",\"TND\":\"TND\",\"TRY\":\"TRY\",\"TWD\":\"TWD\",\"USD\":\"USD\",\"ZAR\":\"ZAR\",\"COP\":\"COP\"}', 0, NULL, NULL, '2019-09-14 13:14:22', '2024-05-07 08:23:05'),
(5, 0, 105, 'PayTM', 'Paytm', '663a390f601191715091727.png', 1, '{\"MID\":{\"title\":\"Merchant ID\",\"global\":true,\"value\":\"DIY12386817555501617\"},\"merchant_key\":{\"title\":\"Merchant Key\",\"global\":true,\"value\":\"bKMfNxPPf_QdZppa\"},\"WEBSITE\":{\"title\":\"Paytm Website\",\"global\":true,\"value\":\"DIYtestingweb\"},\"INDUSTRY_TYPE_ID\":{\"title\":\"Industry Type\",\"global\":true,\"value\":\"Retail\"},\"CHANNEL_ID\":{\"title\":\"CHANNEL ID\",\"global\":true,\"value\":\"WEB\"},\"transaction_url\":{\"title\":\"Transaction URL\",\"global\":true,\"value\":\"https:\\/\\/pguat.paytm.com\\/oltp-web\\/processTransaction\"},\"transaction_status_url\":{\"title\":\"Transaction STATUS URL\",\"global\":true,\"value\":\"https:\\/\\/pguat.paytm.com\\/paytmchecksum\\/paytmCallback.jsp\"}}', '{\"AUD\":\"AUD\",\"ARS\":\"ARS\",\"BDT\":\"BDT\",\"BRL\":\"BRL\",\"BGN\":\"BGN\",\"CAD\":\"CAD\",\"CLP\":\"CLP\",\"CNY\":\"CNY\",\"COP\":\"COP\",\"HRK\":\"HRK\",\"CZK\":\"CZK\",\"DKK\":\"DKK\",\"EGP\":\"EGP\",\"EUR\":\"EUR\",\"GEL\":\"GEL\",\"GHS\":\"GHS\",\"HKD\":\"HKD\",\"HUF\":\"HUF\",\"INR\":\"INR\",\"IDR\":\"IDR\",\"ILS\":\"ILS\",\"JPY\":\"JPY\",\"KES\":\"KES\",\"MYR\":\"MYR\",\"MXN\":\"MXN\",\"MAD\":\"MAD\",\"NPR\":\"NPR\",\"NZD\":\"NZD\",\"NGN\":\"NGN\",\"NOK\":\"NOK\",\"PKR\":\"PKR\",\"PEN\":\"PEN\",\"PHP\":\"PHP\",\"PLN\":\"PLN\",\"RON\":\"RON\",\"RUB\":\"RUB\",\"SGD\":\"SGD\",\"ZAR\":\"ZAR\",\"KRW\":\"KRW\",\"LKR\":\"LKR\",\"SEK\":\"SEK\",\"CHF\":\"CHF\",\"THB\":\"THB\",\"TRY\":\"TRY\",\"UGX\":\"UGX\",\"UAH\":\"UAH\",\"AED\":\"AED\",\"GBP\":\"GBP\",\"USD\":\"USD\",\"VND\":\"VND\",\"XOF\":\"XOF\"}', 0, NULL, NULL, '2019-09-14 13:14:22', '2024-05-07 08:22:07'),
(6, 0, 106, 'Payeer', 'Payeer', '663a38c9e2e931715091657.png', 1, '{\"merchant_id\":{\"title\":\"Merchant ID\",\"global\":true,\"value\":\"866989763\"},\"secret_key\":{\"title\":\"Secret key\",\"global\":true,\"value\":\"7575\"}}', '{\"USD\":\"USD\",\"EUR\":\"EUR\",\"RUB\":\"RUB\"}', 0, '{\"status\":{\"title\": \"Status URL\",\"value\":\"ipn.Payeer\"}}', NULL, '2019-09-14 13:14:22', '2024-05-07 08:20:57'),
(7, 0, 107, 'PayStack', 'Paystack', '663a38fc814e91715091708.png', 1, '{\"public_key\":{\"title\":\"Public key\",\"global\":true,\"value\":\"pk_test_cd330608eb47970889bca397ced55c1dd5ad3783\"},\"secret_key\":{\"title\":\"Secret key\",\"global\":true,\"value\":\"sk_test_8a0b1f199362d7acc9c390bff72c4e81f74e2ac3\"}}', '{\"USD\":\"USD\",\"NGN\":\"NGN\",\"GHS\":\"GHS\",\"KES\":\"KES\",\"ZAR\":\"ZAR\",\"XOF\":\"XOF\"}', 0, '{\"callback\":{\"title\": \"Callback URL\",\"value\":\"ipn.Paystack\"},\"webhook\":{\"title\": \"Webhook URL\",\"value\":\"ipn.Paystack\"}}\r\n', NULL, '2019-09-14 13:14:22', '2024-05-07 08:21:48'),
(9, 0, 109, 'Flutterwave', 'Flutterwave', '663a36c2c34d61715091138.png', 1, '{\"public_key\":{\"title\":\"Public Key\",\"global\":true,\"value\":\"----------------\"},\"secret_key\":{\"title\":\"Secret Key\",\"global\":true,\"value\":\"-----------------------\"},\"encryption_key\":{\"title\":\"Encryption Key\",\"global\":true,\"value\":\"------------------\"}}', '{\"BIF\":\"BIF\",\"CAD\":\"CAD\",\"CDF\":\"CDF\",\"CVE\":\"CVE\",\"EUR\":\"EUR\",\"GBP\":\"GBP\",\"GHS\":\"GHS\",\"GMD\":\"GMD\",\"GNF\":\"GNF\",\"KES\":\"KES\",\"LRD\":\"LRD\",\"MWK\":\"MWK\",\"MZN\":\"MZN\",\"NGN\":\"NGN\",\"RWF\":\"RWF\",\"SLL\":\"SLL\",\"STD\":\"STD\",\"TZS\":\"TZS\",\"UGX\":\"UGX\",\"USD\":\"USD\",\"XAF\":\"XAF\",\"XOF\":\"XOF\",\"ZMK\":\"ZMK\",\"ZMW\":\"ZMW\",\"ZWD\":\"ZWD\"}', 0, NULL, NULL, '2019-09-14 13:14:22', '2024-05-07 08:12:18'),
(10, 0, 110, 'RazorPay', 'Razorpay', '663a393a527831715091770.png', 1, '{\"key_id\":{\"title\":\"Key Id\",\"global\":true,\"value\":\"rzp_test_kiOtejPbRZU90E\"},\"key_secret\":{\"title\":\"Key Secret \",\"global\":true,\"value\":\"osRDebzEqbsE1kbyQJ4y0re7\"}}', '{\"INR\":\"INR\"}', 0, NULL, NULL, '2019-09-14 13:14:22', '2024-05-07 08:22:50'),
(11, 0, 111, 'Stripe Storefront', 'StripeJs', '663a3995417171715091861.png', 1, '{\"secret_key\":{\"title\":\"Secret Key\",\"global\":true,\"value\":\"sk_test_51I6GGiCGv1sRiQlEi5v1or9eR0HVbuzdMd2rW4n3DxC8UKfz66R4X6n4yYkzvI2LeAIuRU9H99ZpY7XCNFC9xMs500vBjZGkKG\"},\"publishable_key\":{\"title\":\"PUBLISHABLE KEY\",\"global\":true,\"value\":\"pk_test_51I6GGiCGv1sRiQlEOisPKrjBqQqqcFsw8mXNaZ2H2baN6R01NulFS7dKFji1NRRxuchoUTEDdB7ujKcyKYSVc0z500eth7otOM\"}}', '{\"USD\":\"USD\",\"AUD\":\"AUD\",\"BRL\":\"BRL\",\"CAD\":\"CAD\",\"CHF\":\"CHF\",\"DKK\":\"DKK\",\"EUR\":\"EUR\",\"GBP\":\"GBP\",\"HKD\":\"HKD\",\"INR\":\"INR\",\"JPY\":\"JPY\",\"MXN\":\"MXN\",\"MYR\":\"MYR\",\"NOK\":\"NOK\",\"NZD\":\"NZD\",\"PLN\":\"PLN\",\"SEK\":\"SEK\",\"SGD\":\"SGD\"}', 0, NULL, NULL, '2019-09-14 13:14:22', '2024-05-07 08:24:21'),
(12, 0, 112, 'Instamojo', 'Instamojo', '663a384d54a111715091533.png', 1, '{\"api_key\":{\"title\":\"API KEY\",\"global\":true,\"value\":\"test_2241633c3bc44a3de84a3b33969\"},\"auth_token\":{\"title\":\"Auth Token\",\"global\":true,\"value\":\"test_279f083f7bebefd35217feef22d\"},\"salt\":{\"title\":\"Salt\",\"global\":true,\"value\":\"19d38908eeff4f58b2ddda2c6d86ca25\"}}', '{\"INR\":\"INR\"}', 0, NULL, NULL, '2019-09-14 13:14:22', '2024-05-07 08:18:53'),
(13, 0, 501, 'Blockchain', 'Blockchain', '663a35efd0c311715090927.png', 0, '{\"api_key\":{\"title\":\"API Key\",\"global\":true,\"value\":\"55529946-05ca-48ff-8710-f279d86b1cc5\"},\"xpub_code\":{\"title\":\"XPUB CODE\",\"global\":true,\"value\":\"xpub6CKQ3xxWyBoFAF83izZCSFUorptEU9AF8TezhtWeMU5oefjX3sFSBw62Lr9iHXPkXmDQJJiHZeTRtD9Vzt8grAYRhvbz4nEvBu3QKELVzFK\"}}', '{\"BTC\":\"BTC\"}', 1, NULL, NULL, '2019-09-14 13:14:22', '2025-03-10 00:25:11'),
(15, 0, 503, 'CoinPayments', 'Coinpayments', '663a36a8d8e1d1715091112.png', 0, '{\"public_key\":{\"title\":\"Public Key\",\"global\":true,\"value\":\"---------------------\"},\"private_key\":{\"title\":\"Private Key\",\"global\":true,\"value\":\"---------------------\"},\"merchant_id\":{\"title\":\"Merchant ID\",\"global\":true,\"value\":\"---------------------\"}}', '{\"BTC\":\"Bitcoin\",\"BTC.LN\":\"Bitcoin (Lightning Network)\",\"LTC\":\"Litecoin\",\"CPS\":\"CPS Coin\",\"VLX\":\"Velas\",\"APL\":\"Apollo\",\"AYA\":\"Aryacoin\",\"BAD\":\"Badcoin\",\"BCD\":\"Bitcoin Diamond\",\"BCH\":\"Bitcoin Cash\",\"BCN\":\"Bytecoin\",\"BEAM\":\"BEAM\",\"BITB\":\"Bean Cash\",\"BLK\":\"BlackCoin\",\"BSV\":\"Bitcoin SV\",\"BTAD\":\"Bitcoin Adult\",\"BTG\":\"Bitcoin Gold\",\"BTT\":\"BitTorrent\",\"CLOAK\":\"CloakCoin\",\"CLUB\":\"ClubCoin\",\"CRW\":\"Crown\",\"CRYP\":\"CrypticCoin\",\"CRYT\":\"CryTrExCoin\",\"CURE\":\"CureCoin\",\"DASH\":\"DASH\",\"DCR\":\"Decred\",\"DEV\":\"DeviantCoin\",\"DGB\":\"DigiByte\",\"DOGE\":\"Dogecoin\",\"EBST\":\"eBoost\",\"EOS\":\"EOS\",\"ETC\":\"Ether Classic\",\"ETH\":\"Ethereum\",\"ETN\":\"Electroneum\",\"EUNO\":\"EUNO\",\"EXP\":\"EXP\",\"Expanse\":\"Expanse\",\"FLASH\":\"FLASH\",\"GAME\":\"GameCredits\",\"GLC\":\"Goldcoin\",\"GRS\":\"Groestlcoin\",\"KMD\":\"Komodo\",\"LOKI\":\"LOKI\",\"LSK\":\"LSK\",\"MAID\":\"MaidSafeCoin\",\"MUE\":\"MonetaryUnit\",\"NAV\":\"NAV Coin\",\"NEO\":\"NEO\",\"NMC\":\"Namecoin\",\"NVST\":\"NVO Token\",\"NXT\":\"NXT\",\"OMNI\":\"OMNI\",\"PINK\":\"PinkCoin\",\"PIVX\":\"PIVX\",\"POT\":\"PotCoin\",\"PPC\":\"Peercoin\",\"PROC\":\"ProCurrency\",\"PURA\":\"PURA\",\"QTUM\":\"QTUM\",\"RES\":\"Resistance\",\"RVN\":\"Ravencoin\",\"RVR\":\"RevolutionVR\",\"SBD\":\"Steem Dollars\",\"SMART\":\"SmartCash\",\"SOXAX\":\"SOXAX\",\"STEEM\":\"STEEM\",\"STRAT\":\"STRAT\",\"SYS\":\"Syscoin\",\"TPAY\":\"TokenPay\",\"TRIGGERS\":\"Triggers\",\"TRX\":\" TRON\",\"UBQ\":\"Ubiq\",\"UNIT\":\"UniversalCurrency\",\"USDT.BEP20\":\"Tether USD (BSC Chain)\",\"USDT.ERC20\":\"Tether USD (ERC20)\",\"USDT.TRC20\":\"Tether USD (Tron/TRC20)\",\"VTC\":\"Vertcoin\",\"WAVES\":\"Waves\",\"XCP\":\"Counterparty\",\"XEM\":\"NEM\",\"XMR\":\"Monero\",\"XSN\":\"Stakenet\",\"XSR\":\"SucreCoin\",\"XVG\":\"VERGE\",\"XZC\":\"ZCoin\",\"ZEC\":\"ZCash\",\"ZEN\":\"Horizen\"}', 1, NULL, NULL, '2019-09-14 13:14:22', '2025-03-10 00:25:40'),
(16, 0, 504, 'CoinPayments Fiat', 'CoinpaymentsFiat', '663a36b7b841a1715091127.png', 1, '{\"merchant_id\":{\"title\":\"Merchant ID\",\"global\":true,\"value\":\"6515561\"}}', '{\"USD\":\"USD\",\"AUD\":\"AUD\",\"BRL\":\"BRL\",\"CAD\":\"CAD\",\"CHF\":\"CHF\",\"CLP\":\"CLP\",\"CNY\":\"CNY\",\"DKK\":\"DKK\",\"EUR\":\"EUR\",\"GBP\":\"GBP\",\"HKD\":\"HKD\",\"INR\":\"INR\",\"ISK\":\"ISK\",\"JPY\":\"JPY\",\"KRW\":\"KRW\",\"NZD\":\"NZD\",\"PLN\":\"PLN\",\"RUB\":\"RUB\",\"SEK\":\"SEK\",\"SGD\":\"SGD\",\"THB\":\"THB\",\"TWD\":\"TWD\"}', 0, NULL, NULL, '2019-09-14 13:14:22', '2024-05-07 08:12:07'),
(17, 0, 505, 'Coingate', 'Coingate', '663a368e753381715091086.png', 0, '{\"api_key\":{\"title\":\"API Key\",\"global\":true,\"value\":\"6354mwVCEw5kHzRJ6thbGo-N\"}}', '{\"USD\":\"USD\",\"EUR\":\"EUR\"}', 0, NULL, NULL, '2019-09-14 13:14:22', '2025-03-10 00:25:37'),
(18, 0, 506, 'Coinbase Commerce', 'CoinbaseCommerce', '663a367e46ae51715091070.png', 0, '{\"api_key\":{\"title\":\"API Key\",\"global\":true,\"value\":\"c47cd7df-d8e8-424b-a20a\"},\"secret\":{\"title\":\"Webhook Shared Secret\",\"global\":true,\"value\":\"55871878-2c32-4f64-ab66\"}}', '{\"USD\":\"USD\",\"EUR\":\"EUR\",\"JPY\":\"JPY\",\"GBP\":\"GBP\",\"AUD\":\"AUD\",\"CAD\":\"CAD\",\"CHF\":\"CHF\",\"CNY\":\"CNY\",\"SEK\":\"SEK\",\"NZD\":\"NZD\",\"MXN\":\"MXN\",\"SGD\":\"SGD\",\"HKD\":\"HKD\",\"NOK\":\"NOK\",\"KRW\":\"KRW\",\"TRY\":\"TRY\",\"RUB\":\"RUB\",\"INR\":\"INR\",\"BRL\":\"BRL\",\"ZAR\":\"ZAR\",\"AED\":\"AED\",\"AFN\":\"AFN\",\"ALL\":\"ALL\",\"AMD\":\"AMD\",\"ANG\":\"ANG\",\"AOA\":\"AOA\",\"ARS\":\"ARS\",\"AWG\":\"AWG\",\"AZN\":\"AZN\",\"BAM\":\"BAM\",\"BBD\":\"BBD\",\"BDT\":\"BDT\",\"BGN\":\"BGN\",\"BHD\":\"BHD\",\"BIF\":\"BIF\",\"BMD\":\"BMD\",\"BND\":\"BND\",\"BOB\":\"BOB\",\"BSD\":\"BSD\",\"BTN\":\"BTN\",\"BWP\":\"BWP\",\"BYN\":\"BYN\",\"BZD\":\"BZD\",\"CDF\":\"CDF\",\"CLF\":\"CLF\",\"CLP\":\"CLP\",\"COP\":\"COP\",\"CRC\":\"CRC\",\"CUC\":\"CUC\",\"CUP\":\"CUP\",\"CVE\":\"CVE\",\"CZK\":\"CZK\",\"DJF\":\"DJF\",\"DKK\":\"DKK\",\"DOP\":\"DOP\",\"DZD\":\"DZD\",\"EGP\":\"EGP\",\"ERN\":\"ERN\",\"ETB\":\"ETB\",\"FJD\":\"FJD\",\"FKP\":\"FKP\",\"GEL\":\"GEL\",\"GGP\":\"GGP\",\"GHS\":\"GHS\",\"GIP\":\"GIP\",\"GMD\":\"GMD\",\"GNF\":\"GNF\",\"GTQ\":\"GTQ\",\"GYD\":\"GYD\",\"HNL\":\"HNL\",\"HRK\":\"HRK\",\"HTG\":\"HTG\",\"HUF\":\"HUF\",\"IDR\":\"IDR\",\"ILS\":\"ILS\",\"IMP\":\"IMP\",\"IQD\":\"IQD\",\"IRR\":\"IRR\",\"ISK\":\"ISK\",\"JEP\":\"JEP\",\"JMD\":\"JMD\",\"JOD\":\"JOD\",\"KES\":\"KES\",\"KGS\":\"KGS\",\"KHR\":\"KHR\",\"KMF\":\"KMF\",\"KPW\":\"KPW\",\"KWD\":\"KWD\",\"KYD\":\"KYD\",\"KZT\":\"KZT\",\"LAK\":\"LAK\",\"LBP\":\"LBP\",\"LKR\":\"LKR\",\"LRD\":\"LRD\",\"LSL\":\"LSL\",\"LYD\":\"LYD\",\"MAD\":\"MAD\",\"MDL\":\"MDL\",\"MGA\":\"MGA\",\"MKD\":\"MKD\",\"MMK\":\"MMK\",\"MNT\":\"MNT\",\"MOP\":\"MOP\",\"MRO\":\"MRO\",\"MUR\":\"MUR\",\"MVR\":\"MVR\",\"MWK\":\"MWK\",\"MYR\":\"MYR\",\"MZN\":\"MZN\",\"NAD\":\"NAD\",\"NGN\":\"NGN\",\"NIO\":\"NIO\",\"NPR\":\"NPR\",\"OMR\":\"OMR\",\"PAB\":\"PAB\",\"PEN\":\"PEN\",\"PGK\":\"PGK\",\"PHP\":\"PHP\",\"PKR\":\"PKR\",\"PLN\":\"PLN\",\"PYG\":\"PYG\",\"QAR\":\"QAR\",\"RON\":\"RON\",\"RSD\":\"RSD\",\"RWF\":\"RWF\",\"SAR\":\"SAR\",\"SBD\":\"SBD\",\"SCR\":\"SCR\",\"SDG\":\"SDG\",\"SHP\":\"SHP\",\"SLL\":\"SLL\",\"SOS\":\"SOS\",\"SRD\":\"SRD\",\"SSP\":\"SSP\",\"STD\":\"STD\",\"SVC\":\"SVC\",\"SYP\":\"SYP\",\"SZL\":\"SZL\",\"THB\":\"THB\",\"TJS\":\"TJS\",\"TMT\":\"TMT\",\"TND\":\"TND\",\"TOP\":\"TOP\",\"TTD\":\"TTD\",\"TWD\":\"TWD\",\"TZS\":\"TZS\",\"UAH\":\"UAH\",\"UGX\":\"UGX\",\"UYU\":\"UYU\",\"UZS\":\"UZS\",\"VEF\":\"VEF\",\"VND\":\"VND\",\"VUV\":\"VUV\",\"WST\":\"WST\",\"XAF\":\"XAF\",\"XAG\":\"XAG\",\"XAU\":\"XAU\",\"XCD\":\"XCD\",\"XDR\":\"XDR\",\"XOF\":\"XOF\",\"XPD\":\"XPD\",\"XPF\":\"XPF\",\"XPT\":\"XPT\",\"YER\":\"YER\",\"ZMW\":\"ZMW\",\"ZWL\":\"ZWL\"}\r\n\r\n', 0, '{\"endpoint\":{\"title\": \"Webhook Endpoint\",\"value\":\"ipn.CoinbaseCommerce\"}}', NULL, '2019-09-14 13:14:22', '2025-03-10 00:25:34'),
(24, 0, 113, 'Paypal Express', 'PaypalSdk', '663a38ed101a61715091693.png', 1, '{\"clientId\":{\"title\":\"Paypal Client ID\",\"global\":true,\"value\":\"Ae0-tixtSV7DvLwIh3Bmu7JvHrjh5EfGdXr_cEklKAVjjezRZ747BxKILiBdzlKKyp-W8W_T7CKH1Ken\"},\"clientSecret\":{\"title\":\"Client Secret\",\"global\":true,\"value\":\"EOhbvHZgFNO21soQJT1L9Q00M3rK6PIEsdiTgXRBt2gtGtxwRer5JvKnVUGNU5oE63fFnjnYY7hq3HBA\"}}', '{\"AUD\":\"AUD\",\"BRL\":\"BRL\",\"CAD\":\"CAD\",\"CZK\":\"CZK\",\"DKK\":\"DKK\",\"EUR\":\"EUR\",\"HKD\":\"HKD\",\"HUF\":\"HUF\",\"INR\":\"INR\",\"ILS\":\"ILS\",\"JPY\":\"JPY\",\"MYR\":\"MYR\",\"MXN\":\"MXN\",\"TWD\":\"TWD\",\"NZD\":\"NZD\",\"NOK\":\"NOK\",\"PHP\":\"PHP\",\"PLN\":\"PLN\",\"GBP\":\"GBP\",\"RUB\":\"RUB\",\"SGD\":\"SGD\",\"SEK\":\"SEK\",\"CHF\":\"CHF\",\"THB\":\"THB\",\"USD\":\"$\"}', 0, NULL, NULL, '2019-09-14 13:14:22', '2024-05-07 08:21:33'),
(25, 0, 114, 'Stripe Checkout', 'StripeV3', '663a39afb519f1715091887.png', 1, '{\"secret_key\":{\"title\":\"Secret Key\",\"global\":true,\"value\":\"sk_test_51I6GGiCGv1sRiQlEi5v1or9eR0HVbuzdMd2rW4n3DxC8UKfz66R4X6n4yYkzvI2LeAIuRU9H99ZpY7XCNFC9xMs500vBjZGkKG\"},\"publishable_key\":{\"title\":\"PUBLISHABLE KEY\",\"global\":true,\"value\":\"pk_test_51I6GGiCGv1sRiQlEOisPKrjBqQqqcFsw8mXNaZ2H2baN6R01NulFS7dKFji1NRRxuchoUTEDdB7ujKcyKYSVc0z500eth7otOM\"},\"end_point\":{\"title\":\"End Point Secret\",\"global\":true,\"value\":\"whsec_lUmit1gtxwKTveLnSe88xCSDdnPOt8g5\"}}', '{\"USD\":\"USD\",\"AUD\":\"AUD\",\"BRL\":\"BRL\",\"CAD\":\"CAD\",\"CHF\":\"CHF\",\"DKK\":\"DKK\",\"EUR\":\"EUR\",\"GBP\":\"GBP\",\"HKD\":\"HKD\",\"INR\":\"INR\",\"JPY\":\"JPY\",\"MXN\":\"MXN\",\"MYR\":\"MYR\",\"NOK\":\"NOK\",\"NZD\":\"NZD\",\"PLN\":\"PLN\",\"SEK\":\"SEK\",\"SGD\":\"SGD\"}', 0, '{\"webhook\":{\"title\": \"Webhook Endpoint\",\"value\":\"ipn.StripeV3\"}}', NULL, '2019-09-14 13:14:22', '2024-05-07 08:24:47'),
(27, 0, 115, 'Mollie', 'Mollie', '663a387ec69371715091582.png', 1, '{\"mollie_email\":{\"title\":\"Mollie Email \",\"global\":true,\"value\":\"vi@gmail.com\"},\"api_key\":{\"title\":\"API KEY\",\"global\":true,\"value\":\"test_cucfwKTWfft9s337qsVfn5CC4vNkrn\"}}', '{\"AED\":\"AED\",\"AUD\":\"AUD\",\"BGN\":\"BGN\",\"BRL\":\"BRL\",\"CAD\":\"CAD\",\"CHF\":\"CHF\",\"CZK\":\"CZK\",\"DKK\":\"DKK\",\"EUR\":\"EUR\",\"GBP\":\"GBP\",\"HKD\":\"HKD\",\"HRK\":\"HRK\",\"HUF\":\"HUF\",\"ILS\":\"ILS\",\"ISK\":\"ISK\",\"JPY\":\"JPY\",\"MXN\":\"MXN\",\"MYR\":\"MYR\",\"NOK\":\"NOK\",\"NZD\":\"NZD\",\"PHP\":\"PHP\",\"PLN\":\"PLN\",\"RON\":\"RON\",\"RUB\":\"RUB\",\"SEK\":\"SEK\",\"SGD\":\"SGD\",\"THB\":\"THB\",\"TWD\":\"TWD\",\"USD\":\"USD\",\"ZAR\":\"ZAR\"}', 0, NULL, NULL, '2019-09-14 13:14:22', '2024-05-07 08:19:42'),
(30, 0, 116, 'Cashmaal', 'Cashmaal', '663a361b16bd11715090971.png', 0, '{\"web_id\":{\"title\":\"Web Id\",\"global\":true,\"value\":\"3748\"},\"ipn_key\":{\"title\":\"IPN Key\",\"global\":true,\"value\":\"546254628759524554647987\"}}', '{\"PKR\":\"PKR\",\"USD\":\"USD\"}', 0, '{\"webhook\":{\"title\": \"IPN URL\",\"value\":\"ipn.Cashmaal\"}}', NULL, NULL, '2025-03-10 00:25:14'),
(36, 0, 119, 'Mercado Pago', 'MercadoPago', '663a386c714a91715091564.png', 1, '{\"access_token\":{\"title\":\"Access Token\",\"global\":true,\"value\":\"APP_USR-7924565816849832-082312-21941521997fab717db925cf1ea2c190-1071840315\"}}', '{\"USD\":\"USD\",\"CAD\":\"CAD\",\"CHF\":\"CHF\",\"DKK\":\"DKK\",\"EUR\":\"EUR\",\"GBP\":\"GBP\",\"NOK\":\"NOK\",\"PLN\":\"PLN\",\"SEK\":\"SEK\",\"AUD\":\"AUD\",\"NZD\":\"NZD\",\"ARS\":\"ARS\",\"BRL\":\"BRL\",\"CLP\":\"CLP\",\"COP\":\"COP\",\"MXN\":\"MXN\",\"PEN\":\"PEN\",\"UYU\":\"UYU\",\"VEF\":\"VEF\",\"BOB\":\"BOB\"}', 0, NULL, NULL, NULL, '2024-05-07 08:19:24'),
(37, 0, 120, 'Authorize.net', 'Authorize', '663a35b9ca5991715090873.png', 0, '{\"login_id\":{\"title\":\"Login ID\",\"global\":true,\"value\":\"59e4P9DBcZv\"},\"transaction_key\":{\"title\":\"Transaction Key\",\"global\":true,\"value\":\"47x47TJyLw2E7DbR\"}}', '{\"USD\":\"USD\",\"CAD\":\"CAD\",\"CHF\":\"CHF\",\"DKK\":\"DKK\",\"EUR\":\"EUR\",\"GBP\":\"GBP\",\"NOK\":\"NOK\",\"PLN\":\"PLN\",\"SEK\":\"SEK\",\"AUD\":\"AUD\",\"NZD\":\"NZD\"}', 0, NULL, NULL, NULL, '2025-03-10 00:25:00'),
(46, 0, 121, 'NMI', 'NMI', '663a3897754cf1715091607.png', 1, '{\"api_key\":{\"title\":\"API Key\",\"global\":true,\"value\":\"2F822Rw39fx762MaV7Yy86jXGTC7sCDy\"}}', '{\"AED\":\"AED\",\"ARS\":\"ARS\",\"AUD\":\"AUD\",\"BOB\":\"BOB\",\"BRL\":\"BRL\",\"CAD\":\"CAD\",\"CHF\":\"CHF\",\"CLP\":\"CLP\",\"CNY\":\"CNY\",\"COP\":\"COP\",\"DKK\":\"DKK\",\"EUR\":\"EUR\",\"GBP\":\"GBP\",\"HKD\":\"HKD\",\"IDR\":\"IDR\",\"ILS\":\"ILS\",\"INR\":\"INR\",\"JPY\":\"JPY\",\"KRW\":\"KRW\",\"MXN\":\"MXN\",\"MYR\":\"MYR\",\"NOK\":\"NOK\",\"NZD\":\"NZD\",\"PEN\":\"PEN\",\"PHP\":\"PHP\",\"PLN\":\"PLN\",\"PYG\":\"PYG\",\"RUB\":\"RUB\",\"SEC\":\"SEC\",\"SGD\":\"SGD\",\"THB\":\"THB\",\"TRY\":\"TRY\",\"TWD\":\"TWD\",\"USD\":\"USD\",\"ZAR\":\"ZAR\"}', 0, NULL, NULL, NULL, '2024-05-07 08:20:07'),
(50, 0, 507, 'BTCPay', 'BTCPay', '663a35cd25a8d1715090893.png', 0, '{\"store_id\":{\"title\":\"Store Id\",\"global\":true,\"value\":\"HsqFVTXSeUFJu7caoYZc3CTnP8g5LErVdHhEXPVTheHf\"},\"api_key\":{\"title\":\"Api Key\",\"global\":true,\"value\":\"4436bd706f99efae69305e7c4eff4780de1335ce\"},\"server_name\":{\"title\":\"Server Name\",\"global\":true,\"value\":\"https:\\/\\/testnet.demo.btcpayserver.org\"},\"secret_code\":{\"title\":\"Secret Code\",\"global\":true,\"value\":\"SUCdqPn9CDkY7RmJHfpQVHP2Lf2\"}}', '{\"BTC\":\"Bitcoin\",\"LTC\":\"Litecoin\"}', 1, '{\"webhook\":{\"title\": \"IPN URL\",\"value\":\"ipn.BTCPay\"}}', NULL, NULL, '2025-03-10 00:25:02'),
(51, 0, 508, 'Now payments hosted', 'NowPaymentsHosted', '663a38b8d57a81715091640.png', 1, '{\"api_key\":{\"title\":\"API Key\",\"global\":true,\"value\":\"--------\"},\"secret_key\":{\"title\":\"Secret Key\",\"global\":true,\"value\":\"------------\"}}', '{\"BTG\":\"BTG\",\"ETH\":\"ETH\",\"XMR\":\"XMR\",\"ZEC\":\"ZEC\",\"XVG\":\"XVG\",\"ADA\":\"ADA\",\"LTC\":\"LTC\",\"BCH\":\"BCH\",\"QTUM\":\"QTUM\",\"DASH\":\"DASH\",\"XLM\":\"XLM\",\"XRP\":\"XRP\",\"XEM\":\"XEM\",\"DGB\":\"DGB\",\"LSK\":\"LSK\",\"DOGE\":\"DOGE\",\"TRX\":\"TRX\",\"KMD\":\"KMD\",\"REP\":\"REP\",\"BAT\":\"BAT\",\"ARK\":\"ARK\",\"WAVES\":\"WAVES\",\"BNB\":\"BNB\",\"XZC\":\"XZC\",\"NANO\":\"NANO\",\"TUSD\":\"TUSD\",\"VET\":\"VET\",\"ZEN\":\"ZEN\",\"GRS\":\"GRS\",\"FUN\":\"FUN\",\"NEO\":\"NEO\",\"GAS\":\"GAS\",\"PAX\":\"PAX\",\"USDC\":\"USDC\",\"ONT\":\"ONT\",\"XTZ\":\"XTZ\",\"LINK\":\"LINK\",\"RVN\":\"RVN\",\"BNBMAINNET\":\"BNBMAINNET\",\"ZIL\":\"ZIL\",\"BCD\":\"BCD\",\"USDT\":\"USDT\",\"USDTERC20\":\"USDTERC20\",\"CRO\":\"CRO\",\"DAI\":\"DAI\",\"HT\":\"HT\",\"WABI\":\"WABI\",\"BUSD\":\"BUSD\",\"ALGO\":\"ALGO\",\"USDTTRC20\":\"USDTTRC20\",\"GT\":\"GT\",\"STPT\":\"STPT\",\"AVA\":\"AVA\",\"SXP\":\"SXP\",\"UNI\":\"UNI\",\"OKB\":\"OKB\",\"BTC\":\"BTC\"}', 1, '', NULL, NULL, '2024-05-07 08:20:40'),
(52, 0, 509, 'Now payments checkout', 'NowPaymentsCheckout', '663a38a59d2541715091621.png', 1, '{\"api_key\":{\"title\":\"API Key\",\"global\":true,\"value\":\"---------------\"},\"secret_key\":{\"title\":\"Secret Key\",\"global\":true,\"value\":\"-----------\"}}', '{\"USD\":\"USD\",\"EUR\":\"EUR\"}', 1, '', NULL, NULL, '2024-05-07 08:20:21'),
(53, 0, 122, '2Checkout', 'TwoCheckout', '663a39b8e64b91715091896.png', 1, '{\"merchant_code\":{\"title\":\"Merchant Code\",\"global\":true,\"value\":\"253248016872\"},\"secret_key\":{\"title\":\"Secret Key\",\"global\":true,\"value\":\"eQM)ID@&vG84u!O*g[p+\"}}', '{\"AFN\": \"AFN\",\"ALL\": \"ALL\",\"DZD\": \"DZD\",\"ARS\": \"ARS\",\"AUD\": \"AUD\",\"AZN\": \"AZN\",\"BSD\": \"BSD\",\"BDT\": \"BDT\",\"BBD\": \"BBD\",\"BZD\": \"BZD\",\"BMD\": \"BMD\",\"BOB\": \"BOB\",\"BWP\": \"BWP\",\"BRL\": \"BRL\",\"GBP\": \"GBP\",\"BND\": \"BND\",\"BGN\": \"BGN\",\"CAD\": \"CAD\",\"CLP\": \"CLP\",\"CNY\": \"CNY\",\"COP\": \"COP\",\"CRC\": \"CRC\",\"HRK\": \"HRK\",\"CZK\": \"CZK\",\"DKK\": \"DKK\",\"DOP\": \"DOP\",\"XCD\": \"XCD\",\"EGP\": \"EGP\",\"EUR\": \"EUR\",\"FJD\": \"FJD\",\"GTQ\": \"GTQ\",\"HKD\": \"HKD\",\"HNL\": \"HNL\",\"HUF\": \"HUF\",\"INR\": \"INR\",\"IDR\": \"IDR\",\"ILS\": \"ILS\",\"JMD\": \"JMD\",\"JPY\": \"JPY\",\"KZT\": \"KZT\",\"KES\": \"KES\",\"LAK\": \"LAK\",\"MMK\": \"MMK\",\"LBP\": \"LBP\",\"LRD\": \"LRD\",\"MOP\": \"MOP\",\"MYR\": \"MYR\",\"MVR\": \"MVR\",\"MRO\": \"MRO\",\"MUR\": \"MUR\",\"MXN\": \"MXN\",\"MAD\": \"MAD\",\"NPR\": \"NPR\",\"TWD\": \"TWD\",\"NZD\": \"NZD\",\"NIO\": \"NIO\",\"NOK\": \"NOK\",\"PKR\": \"PKR\",\"PGK\": \"PGK\",\"PEN\": \"PEN\",\"PHP\": \"PHP\",\"PLN\": \"PLN\",\"QAR\": \"QAR\",\"RON\": \"RON\",\"RUB\": \"RUB\",\"WST\": \"WST\",\"SAR\": \"SAR\",\"SCR\": \"SCR\",\"SGD\": \"SGD\",\"SBD\": \"SBD\",\"ZAR\": \"ZAR\",\"KRW\": \"KRW\",\"LKR\": \"LKR\",\"SEK\": \"SEK\",\"CHF\": \"CHF\",\"SYP\": \"SYP\",\"THB\": \"THB\",\"TOP\": \"TOP\",\"TTD\": \"TTD\",\"TRY\": \"TRY\",\"UAH\": \"UAH\",\"AED\": \"AED\",\"USD\": \"USD\",\"VUV\": \"VUV\",\"VND\": \"VND\",\"XOF\": \"XOF\",\"YER\": \"YER\"}', 0, '{\"approved_url\":{\"title\": \"Approved URL\",\"value\":\"ipn.TwoCheckout\"}}', NULL, NULL, '2024-05-07 08:24:56'),
(54, 0, 123, 'Checkout', 'Checkout', '663a3628733351715090984.png', 0, '{\"secret_key\":{\"title\":\"Secret Key\",\"global\":true,\"value\":\"------\"},\"public_key\":{\"title\":\"PUBLIC KEY\",\"global\":true,\"value\":\"------\"},\"processing_channel_id\":{\"title\":\"PROCESSING CHANNEL\",\"global\":true,\"value\":\"------\"}}', '{\"USD\":\"USD\",\"EUR\":\"EUR\",\"GBP\":\"GBP\",\"HKD\":\"HKD\",\"AUD\":\"AUD\",\"CAN\":\"CAN\",\"CHF\":\"CHF\",\"SGD\":\"SGD\",\"JPY\":\"JPY\",\"NZD\":\"NZD\"}', 0, NULL, NULL, NULL, '2025-03-10 00:25:31'),
(56, 0, 510, 'Binance', 'Binance', '663a35db4fd621715090907.png', 0, '{\"api_key\":{\"title\":\"API Key\",\"global\":true,\"value\":\"tsu3tjiq0oqfbtmlbevoeraxhfbp3brejnm9txhjxcp4to29ujvakvfl1ibsn3ja\"},\"secret_key\":{\"title\":\"Secret Key\",\"global\":true,\"value\":\"jzngq4t04ltw8d4iqpi7admfl8tvnpehxnmi34id1zvfaenbwwvsvw7llw3zdko8\"},\"merchant_id\":{\"title\":\"Merchant ID\",\"global\":true,\"value\":\"231129033\"}}', '{\"BTC\":\"Bitcoin\",\"USD\":\"USD\",\"BNB\":\"BNB\"}', 1, '{\"cron\":{\"title\": \"Cron Job URL\",\"value\":\"ipn.Binance\"}}', NULL, NULL, '2025-03-10 00:25:08'),
(57, 0, 124, 'SslCommerz', 'SslCommerz', '663a397a70c571715091834.png', 1, '{\"store_id\":{\"title\":\"Store ID\",\"global\":true,\"value\":\"---------\"},\"store_password\":{\"title\":\"Store Password\",\"global\":true,\"value\":\"----------\"}}', '{\"BDT\":\"BDT\",\"USD\":\"USD\",\"EUR\":\"EUR\",\"SGD\":\"SGD\",\"INR\":\"INR\",\"MYR\":\"MYR\"}', 0, NULL, NULL, NULL, '2024-05-07 08:23:54'),
(58, 0, 125, 'Aamarpay', 'Aamarpay', '663a34d5d1dfc1715090645.png', 0, '{\"store_id\":{\"title\":\"Store ID\",\"global\":true,\"value\":\"---------\"},\"signature_key\":{\"title\":\"Signature Key\",\"global\":true,\"value\":\"----------\"}}', '{\"BDT\":\"BDT\"}', 0, NULL, NULL, NULL, '2025-03-10 00:24:58'),
(61, 0, 126, 'bKash', 'BKash', '67e1432683b5a1742816038.png', 1, '{\"username\":{\"title\":\"Username\",\"global\":true,\"value\":\"01770618567\"},\"password\":{\"title\":\"Password\",\"global\":true,\"value\":\"D7DaC<*E*eG\"},\"app_key\":{\"title\":\"App Key\",\"global\":true,\"value\":\"0vWQuCRGiUX7EPVjQDr0EUAYtc\"},\"app_secret\":{\"title\":\"App Secret\",\"global\":true,\"value\":\"jcUNPBgbcqEDedNKdvE4G1cAK7D3hCjmJccNPZZBq96QIxxwAMEx\"}}', '{\"BDT\":\"BDT\"}', 0, NULL, NULL, NULL, '2025-03-16 03:58:31');

-- --------------------------------------------------------

--
-- Table structure for table `gateway_currencies`
--

CREATE TABLE `gateway_currencies` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `symbol` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `method_code` int DEFAULT NULL,
  `gateway_alias` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `min_amount` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `max_amount` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `percent_charge` decimal(5,2) NOT NULL DEFAULT '0.00',
  `fixed_charge` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `rate` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `gateway_parameter` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `general_settings`
--

CREATE TABLE `general_settings` (
  `id` bigint UNSIGNED NOT NULL,
  `site_name` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cur_text` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'currency text',
  `cur_sym` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'currency symbol',
  `email_from` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_from_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_template` text COLLATE utf8mb4_unicode_ci,
  `sms_template` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sms_from` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `push_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `push_template` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `base_color` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `secondary_color` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mail_config` text COLLATE utf8mb4_unicode_ci COMMENT 'email configuration',
  `sms_config` text COLLATE utf8mb4_unicode_ci,
  `firebase_config` text COLLATE utf8mb4_unicode_ci,
  `global_shortcodes` text COLLATE utf8mb4_unicode_ci,
  `kv` tinyint(1) NOT NULL DEFAULT '0',
  `ev` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'email verification, 0 - dont check, 1 - check',
  `en` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'email notification, 0 - dont send, 1 - send',
  `sv` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'mobile verication, 0 - dont check, 1 - check',
  `sn` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'sms notification, 0 - dont send, 1 - send',
  `pn` tinyint(1) NOT NULL DEFAULT '1',
  `force_ssl` tinyint(1) NOT NULL DEFAULT '0',
  `in_app_payment` tinyint(1) NOT NULL DEFAULT '1',
  `maintenance_mode` tinyint(1) NOT NULL DEFAULT '0',
  `secure_password` tinyint(1) NOT NULL DEFAULT '0',
  `agree` tinyint(1) NOT NULL DEFAULT '0',
  `multi_language` tinyint(1) NOT NULL DEFAULT '1',
  `registration` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0: Off	, 1: On',
  `buyer_registration` tinyint(1) NOT NULL DEFAULT '0',
  `active_template` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `socialite_credentials` text COLLATE utf8mb4_unicode_ci,
  `socialite_buyer_credentials` text COLLATE utf8mb4_unicode_ci,
  `pusher_config` text COLLATE utf8mb4_unicode_ci,
  `last_cron` datetime DEFAULT NULL,
  `available_version` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `system_customized` tinyint(1) NOT NULL DEFAULT '0',
  `paginate_number` int NOT NULL DEFAULT '0',
  `currency_format` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1=>Both\r\n2=>Text Only\r\n3=>Symbol Only',
  `config_progress` text COLLATE utf8mb4_unicode_ci,
  `escrow_payment` tinyint(1) NOT NULL DEFAULT '1',
  `job_auto_approved` tinyint(1) NOT NULL DEFAULT '0',
  `percent_service_charge` tinyint(1) NOT NULL DEFAULT '0',
  `fixed_service_charge` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `trial_task` tinyint(1) NOT NULL DEFAULT '0',
  `fixed_trial_task_charge` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `general_settings`
--

INSERT INTO `general_settings` (`id`, `site_name`, `cur_text`, `cur_sym`, `email_from`, `email_from_name`, `email_template`, `sms_template`, `sms_from`, `push_title`, `push_template`, `base_color`, `secondary_color`, `mail_config`, `sms_config`, `firebase_config`, `global_shortcodes`, `kv`, `ev`, `en`, `sv`, `sn`, `pn`, `force_ssl`, `in_app_payment`, `maintenance_mode`, `secure_password`, `agree`, `multi_language`, `registration`, `buyer_registration`, `active_template`, `socialite_credentials`, `socialite_buyer_credentials`, `pusher_config`, `last_cron`, `available_version`, `system_customized`, `paginate_number`, `currency_format`, `config_progress`, `escrow_payment`, `job_auto_approved`, `percent_service_charge`, `fixed_service_charge`, `trial_task`, `fixed_trial_task_charge`, `created_at`, `updated_at`) VALUES
(1, 'Olance', 'USD', '$', 'info@viserlab.com', '{{site_name}}', '<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">\r\n  <!--[if !mso]><!-->\r\n  <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">\r\n  <!--<![endif]-->\r\n  <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\r\n  <title></title>\r\n  <style type=\"text/css\">\r\n.ReadMsgBody { width: 100%; background-color: #ffffff; }\r\n.ExternalClass { width: 100%; background-color: #ffffff; }\r\n.ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div { line-height: 100%; }\r\nhtml { width: 100%; }\r\nbody { -webkit-text-size-adjust: none; -ms-text-size-adjust: none; margin: 0; padding: 0; }\r\ntable { border-spacing: 0; table-layout: fixed; margin: 0 auto;border-collapse: collapse; }\r\ntable table table { table-layout: auto; }\r\n.yshortcuts a { border-bottom: none !important; }\r\nimg:hover { opacity: 0.9 !important; }\r\na { color: #0087ff; text-decoration: none; }\r\n.textbutton a { font-family: \'open sans\', arial, sans-serif !important;}\r\n.btn-link a { color:#FFFFFF !important;}\r\n\r\n@media only screen and (max-width: 480px) {\r\nbody { width: auto !important; }\r\n*[class=\"table-inner\"] { width: 90% !important; text-align: center !important; }\r\n*[class=\"table-full\"] { width: 100% !important; text-align: center !important; }\r\n/* image */\r\nimg[class=\"img1\"] { width: 100% !important; height: auto !important; }\r\n}\r\n</style>\r\n\r\n\r\n\r\n  <table bgcolor=\"#414a51\" width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n    <tbody><tr>\r\n      <td height=\"50\"></td>\r\n    </tr>\r\n    <tr>\r\n      <td align=\"center\" style=\"text-align:center;vertical-align:top;font-size:0;\">\r\n        <table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n          <tbody><tr>\r\n            <td align=\"center\" width=\"600\">\r\n              <!--header-->\r\n              <table class=\"table-inner\" width=\"95%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n                <tbody><tr>\r\n                  <td bgcolor=\"#0087ff\" style=\"border-top-left-radius:6px; border-top-right-radius:6px;text-align:center;vertical-align:top;font-size:0;\" align=\"center\">\r\n                    <table width=\"90%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n                      <tbody><tr>\r\n                        <td height=\"20\"></td>\r\n                      </tr>\r\n                      <tr>\r\n                        <td align=\"center\" style=\"font-family: \'Open sans\', Arial, sans-serif; color:#FFFFFF; font-size:16px; font-weight: bold;\">This is a System Generated Email</td>\r\n                      </tr>\r\n                      <tr>\r\n                        <td height=\"20\"></td>\r\n                      </tr>\r\n                    </tbody></table>\r\n                  </td>\r\n                </tr>\r\n              </tbody></table>\r\n              <!--end header-->\r\n              <table class=\"table-inner\" width=\"95%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\r\n                <tbody><tr>\r\n                  <td bgcolor=\"#FFFFFF\" align=\"center\" style=\"text-align:center;vertical-align:top;font-size:0;\">\r\n                    <table align=\"center\" width=\"90%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\r\n                      <tbody><tr>\r\n                        <td height=\"35\"></td>\r\n                      </tr>\r\n                      <!--logo-->\r\n                      <tr>\r\n                        <td align=\"center\" style=\"vertical-align:top;font-size:0;\">\r\n                          <a href=\"#\">\r\n                            <img style=\"display:block; line-height:0px; font-size:0px; border:0px;\" src=\"https://i.imgur.com/Z1qtvtV.png\" width=\"220\" alt=\"img\">\r\n                          </a>\r\n                        </td>\r\n                      </tr>\r\n                      <!--end logo-->\r\n                      <tr>\r\n                        <td height=\"40\"></td>\r\n                      </tr>\r\n                      <!--headline-->\r\n                      <tr>\r\n                        <td align=\"center\" style=\"font-family: \'Open Sans\', Arial, sans-serif; font-size: 22px;color:#414a51;font-weight: bold;\">Hello {{fullname}} ({{username}})</td>\r\n                      </tr>\r\n                      <!--end headline-->\r\n                      <tr>\r\n                        <td align=\"center\" style=\"text-align:center;vertical-align:top;font-size:0;\">\r\n                          <table width=\"40\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n                            <tbody><tr>\r\n                              <td height=\"20\" style=\" border-bottom:3px solid #0087ff;\"></td>\r\n                            </tr>\r\n                          </tbody></table>\r\n                        </td>\r\n                      </tr>\r\n                      <tr>\r\n                        <td height=\"20\"></td>\r\n                      </tr>\r\n                      <!--content-->\r\n                      <tr>\r\n                        <td align=\"left\" style=\"font-family: \'Open sans\', Arial, sans-serif; color:#7f8c8d; font-size:16px; line-height: 28px;\">{{message}}</td>\r\n                      </tr>\r\n                      <!--end content-->\r\n                      <tr>\r\n                        <td height=\"40\"></td>\r\n                      </tr>\r\n              \r\n                    </tbody></table>\r\n                  </td>\r\n                </tr>\r\n                <tr>\r\n                  <td height=\"45\" align=\"center\" bgcolor=\"#f4f4f4\" style=\"border-bottom-left-radius:6px;border-bottom-right-radius:6px;\">\r\n                    <table align=\"center\" width=\"90%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\r\n                      <tbody><tr>\r\n                        <td height=\"10\"></td>\r\n                      </tr>\r\n                      <!--preference-->\r\n                      <tr>\r\n                        <td class=\"preference-link\" align=\"center\" style=\"font-family: \'Open sans\', Arial, sans-serif; color:#95a5a6; font-size:14px;\">\r\n                          © 2024 <a href=\"#\">{{site_name}}</a>&nbsp;. All Rights Reserved. \r\n                        </td>\r\n                      </tr>\r\n                      <!--end preference-->\r\n                      <tr>\r\n                        <td height=\"10\"></td>\r\n                      </tr>\r\n                    </tbody></table>\r\n                  </td>\r\n                </tr>\r\n              </tbody></table>\r\n            </td>\r\n          </tr>\r\n        </tbody></table>\r\n      </td>\r\n    </tr>\r\n    <tr>\r\n      <td height=\"60\"></td>\r\n    </tr>\r\n  </tbody></table>', 'hi {{fullname}} ({{username}}), {{message}}', '{{site_name}}', '{{site_name}}', 'hi {{fullname}} ({{username}}), {{message}}', '14a800', '063862', '{\"name\":\"php\"}', '{\"name\":\"clickatell\",\"clickatell\":{\"api_key\":\"----------------\"},\"infobip\":{\"username\":\"------------8888888\",\"password\":\"-----------------\"},\"message_bird\":{\"api_key\":\"-------------------\"},\"nexmo\":{\"api_key\":\"----------------------\",\"api_secret\":\"----------------------\"},\"sms_broadcast\":{\"username\":\"----------------------\",\"password\":\"-----------------------------\"},\"twilio\":{\"account_sid\":\"-----------------------\",\"auth_token\":\"---------------------------\",\"from\":\"----------------------\"},\"text_magic\":{\"username\":\"-----------------------\",\"apiv2_key\":\"-------------------------------\"},\"custom\":{\"method\":\"get\",\"url\":\"https:\\/\\/hostname.com\\/demo-api-v1\",\"headers\":{\"name\":[\"api_key\"],\"value\":[\"test_api 555\"]},\"body\":{\"name\":[\"from_number\"],\"value\":[\"5657545757\"]}}}', '{\"apiKey\":\"----------------------------\",\"authDomain\":\"----------------------------\",\"projectId\":\"----------------------------\",\"storageBucket\":\"----------------------------\",\"messagingSenderId\":\"----------------------------\",\"appId\":\"----------------------------\",\"measurementId\":\"----------------------------\"}', '{\n    \"site_name\":\"Name of your site\",\n    \"site_currency\":\"Currency of your site\",\n    \"currency_symbol\":\"Symbol of currency\"\n}', 1, 0, 1, 0, 0, 0, 0, 0, 0, 0, 1, 1, 1, 1, 'basic', '{\"google\":{\"client_id\":\"------------------\",\"client_secret\":\"--------------------\",\"status\":1},\"facebook\":{\"client_id\":\"------------\",\"client_secret\":\"----------------\",\"status\":1},\"linkedin\":{\"client_id\":\"-----------------\",\"client_secret\":\"----------------\",\"status\":1}}', '{\"google\":{\"client_id\":\"---------------\",\"client_secret\":\"------------------\",\"status\":1},\"facebook\":{\"client_id\":\"--------------\",\"client_secret\":\"-----------------\",\"status\":1},\"linkedin\":{\"client_id\":\"--------------------\",\"client_secret\":\"-------------------\",\"status\":1}}', '{\"app_key\":\"----------------------------\",\"app_id\":\"----------------------------\",\"app_secret_key\":\"----------------------------\",\"cluster\":\"----------------------------\"}', '2024-06-27 10:36:16', '1.3', 0, 20, 1, '[]', 1, 0, 1, 5.00000000, 0, 0.00000000, NULL, '2026-01-03 03:25:56');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` int NOT NULL,
  `buyer_id` int NOT NULL DEFAULT '0',
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_id` int NOT NULL DEFAULT '0',
  `subcategory_id` int NOT NULL DEFAULT '0',
  `budget` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `custom_budget` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1 =>Enable ,0 => Disabled',
  `description` text COLLATE utf8mb4_unicode_ci,
  `project_scope` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Large => 1, Medium=>2, Small->3',
  `job_longevity` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'job_longevity: 3 to 6 months=>4, 1 to 3 months=>3,Less than 1 month=>2 , Less than 1 Week''=>1',
  `skill_level` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Pro Level=>1, Expert=>2, Intermediate=>3, Entry=>4',
  `questions` text COLLATE utf8mb4_unicode_ci,
  `deadline` date DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0=>Draft, 1=>Published, 2=>''Processing'', 3=> ''Completed'', 4=> ''Finished''',
  `is_approved` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0->Onward ,1->Approved, 3-> Rejected',
  `rejection_reason` text COLLATE utf8mb4_unicode_ci,
  `interviews` int NOT NULL DEFAULT '0' COMMENT 'total_interview count of this job , against bids',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_skills`
--

CREATE TABLE `job_skills` (
  `id` bigint NOT NULL,
  `job_id` int NOT NULL DEFAULT '0',
  `skill_id` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE `languages` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0: not default language, 1: default language',
  `image` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`id`, `name`, `code`, `is_default`, `image`, `created_at`, `updated_at`) VALUES
(1, 'English', 'en', 1, '660b94fa876ac1712035066.png', '2020-07-06 03:47:55', '2024-04-01 23:17:46');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` bigint NOT NULL,
  `conversation_id` int NOT NULL DEFAULT '0',
  `admin_id` int NOT NULL DEFAULT '0',
  `user_id` int NOT NULL DEFAULT '0',
  `buyer_id` int NOT NULL DEFAULT '0',
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `files` text COLLATE utf8mb4_unicode_ci,
  `action` tinyint(1) NOT NULL DEFAULT '0',
  `read_at` datetime DEFAULT NULL COMMENT 'Filled here if user seen msg',
  `buyer_read_at` datetime DEFAULT NULL COMMENT 'Filled here if buyer seen msg',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notification_logs`
--

CREATE TABLE `notification_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL DEFAULT '0',
  `buyer_id` int NOT NULL DEFAULT '0',
  `sender` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sent_from` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sent_to` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` text COLLATE utf8mb4_unicode_ci,
  `notification_type` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_read` tinyint NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notification_templates`
--

CREATE TABLE `notification_templates` (
  `id` bigint UNSIGNED NOT NULL,
  `act` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `push_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_body` text COLLATE utf8mb4_unicode_ci,
  `sms_body` text COLLATE utf8mb4_unicode_ci,
  `push_body` text COLLATE utf8mb4_unicode_ci,
  `shortcodes` text COLLATE utf8mb4_unicode_ci,
  `email_status` tinyint(1) NOT NULL DEFAULT '1',
  `email_sent_from_name` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_sent_from_address` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sms_status` tinyint(1) NOT NULL DEFAULT '1',
  `sms_sent_from` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `push_status` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notification_templates`
--

INSERT INTO `notification_templates` (`id`, `act`, `name`, `subject`, `push_title`, `email_body`, `sms_body`, `push_body`, `shortcodes`, `email_status`, `email_sent_from_name`, `email_sent_from_address`, `sms_status`, `sms_sent_from`, `push_status`, `created_at`, `updated_at`) VALUES
(1, 'BAL_ADD', 'Balance - Added', 'Your Account has been Credited', '{{site_name}} - Balance Added', '<div>We\'re writing to inform you that an amount of {{amount}} {{site_currency}} has been successfully added to your account.</div><div><br></div><div>Here are the details of the transaction:</div><div><br></div><div><b>Transaction Number: </b>{{trx}}</div><div><b>Current Balance:</b> {{post_balance}} {{site_currency}}</div><div><b>Admin Note:</b> {{remark}}</div><div><br></div><div>If you have any questions or require further assistance, please don\'t hesitate to contact us. We\'re here to assist you.</div>', 'We\'re writing to inform you that an amount of {{amount}} {{site_currency}} has been successfully added to your account.', '{{amount}} {{site_currency}} has been successfully added to your account.', '{\"trx\":\"Transaction number for the action\",\"amount\":\"Amount inserted by the admin\",\"remark\":\"Remark inserted by the admin\",\"post_balance\":\"Balance of the user after this transaction\"}', 1, '{{site_name}} Finance', NULL, 0, NULL, 1, '2021-11-03 12:00:00', '2024-05-25 00:49:44'),
(2, 'BAL_SUB', 'Balance - Subtracted', 'Your Account has been Debited', '{{site_name}} - Balance Subtracted', '<div>We wish to inform you that an amount of {{amount}} {{site_currency}} has been successfully deducted from your account.</div><div><br></div><div>Below are the details of the transaction:</div><div><br></div><div><b>Transaction Number:</b> {{trx}}</div><div><b>Current Balance: </b>{{post_balance}} {{site_currency}}</div><div><b>Admin Note:</b> {{remark}}</div><div><br></div><div>Should you require any further clarification or assistance, please do not hesitate to reach out to us. We are here to assist you in any way we can.</div><div><br></div><div>Thank you for your continued trust in {{site_name}}.</div>', 'We wish to inform you that an amount of {{amount}} {{site_currency}} has been successfully deducted from your account.', '{{amount}} {{site_currency}} debited from your account.', '{\"trx\":\"Transaction number for the action\",\"amount\":\"Amount inserted by the admin\",\"remark\":\"Remark inserted by the admin\",\"post_balance\":\"Balance of the user after this transaction\"}', 1, '{{site_name}} Finance', NULL, 1, NULL, 0, '2021-11-03 12:00:00', '2024-05-08 07:17:48'),
(3, 'DEPOSIT_COMPLETE', 'Deposit - Automated - Successful', 'Deposit Completed Successfully', '{{site_name}} - Deposit successful', '<div>We\'re delighted to inform you that your deposit of {{amount}} {{site_currency}} via {{method_name}} has been completed.</div><div><br></div><div>Below, you\'ll find the details of your deposit:</div><div><br></div><div><b>Amount:</b> {{amount}} {{site_currency}}</div><div><b>Charge: </b>{{charge}} {{site_currency}}</div><div><b>Conversion Rate:</b> 1 {{site_currency}} = {{rate}} {{method_currency}}</div><div><b>Received:</b> {{method_amount}} {{method_currency}}</div><div><b>Paid via:</b> {{method_name}}</div><div><b>Transaction Number:</b> {{trx}}</div><div><br></div><div>Your current balance stands at {{post_balance}} {{site_currency}}.</div><div><br></div><div>If you have any questions or need further assistance, feel free to reach out to our support team. We\'re here to assist you in any way we can.</div>', 'We\'re delighted to inform you that your deposit of {{amount}} {{site_currency}} via {{method_name}} has been completed.', 'Deposit Completed Successfully', '{\"trx\":\"Transaction number for the deposit\",\"amount\":\"Amount inserted by the user\",\"charge\":\"Gateway charge set by the admin\",\"rate\":\"Conversion rate between base currency and method currency\",\"method_name\":\"Name of the deposit method\",\"method_currency\":\"Currency of the deposit method\",\"method_amount\":\"Amount after conversion between base currency and method currency\",\"post_balance\":\"Balance of the user after this transaction\"}', 1, '{{site_name}} Billing', NULL, 1, NULL, 1, '2021-11-03 12:00:00', '2024-05-08 07:20:34'),
(4, 'DEPOSIT_APPROVE', 'Deposit - Manual - Approved', 'Deposit Request Approved', '{{site_name}} - Deposit Request Approved', '<div>We are pleased to inform you that your deposit request of {{amount}} {{site_currency}} via {{method_name}} has been approved.</div><div><br></div><div>Here are the details of your deposit:</div><div><br></div><div><b>Amount:</b> {{amount}} {{site_currency}}</div><div><b>Charge: </b>{{charge}} {{site_currency}}</div><div><b>Conversion Rate:</b> 1 {{site_currency}} = {{rate}} {{method_currency}}</div><div><b>Received: </b>{{method_amount}} {{method_currency}}</div><div><b>Paid via: </b>{{method_name}}</div><div><b>Transaction Number: </b>{{trx}}</div><div><br></div><div>Your current balance now stands at {{post_balance}} {{site_currency}}.</div><div><br></div><div>Should you have any questions or require further assistance, please feel free to contact our support team. We\'re here to help.</div>', 'We are pleased to inform you that your deposit request of {{amount}} {{site_currency}} via {{method_name}} has been approved.', 'Deposit of {{amount}} {{site_currency}} via {{method_name}} has been approved.', '{\"trx\":\"Transaction number for the deposit\",\"amount\":\"Amount inserted by the user\",\"charge\":\"Gateway charge set by the admin\",\"rate\":\"Conversion rate between base currency and method currency\",\"method_name\":\"Name of the deposit method\",\"method_currency\":\"Currency of the deposit method\",\"method_amount\":\"Amount after conversion between base currency and method currency\",\"post_balance\":\"Balance of the user after this transaction\"}', 1, '{{site_name}} Billing', NULL, 1, NULL, 0, '2021-11-03 12:00:00', '2024-05-08 07:19:49'),
(5, 'DEPOSIT_REJECT', 'Deposit - Manual - Rejected', 'Deposit Request Rejected', '{{site_name}} - Deposit Request Rejected', '<div>We regret to inform you that your deposit request of {{amount}} {{site_currency}} via {{method_name}} has been rejected.</div><div><br></div><div>Here are the details of the rejected deposit:</div><div><br></div><div><b>Conversion Rate:</b> 1 {{site_currency}} = {{rate}} {{method_currency}}</div><div><b>Received:</b> {{method_amount}} {{method_currency}}</div><div><b>Paid via:</b> {{method_name}}</div><div><b>Charge:</b> {{charge}}</div><div><b>Transaction Number:</b> {{trx}}</div><div><br></div><div>If you have any questions or need further clarification, please don\'t hesitate to contact us. We\'re here to assist you.</div><div><br></div><div>Rejection Reason:</div><div>{{rejection_message}}</div><div><br></div><div>Thank you for your understanding.</div>', 'We regret to inform you that your deposit request of {{amount}} {{site_currency}} via {{method_name}} has been rejected.', 'Your deposit request of {{amount}} {{site_currency}} via {{method_name}} has been rejected.', '{\"trx\":\"Transaction number for the deposit\",\"amount\":\"Amount inserted by the user\",\"charge\":\"Gateway charge set by the admin\",\"rate\":\"Conversion rate between base currency and method currency\",\"method_name\":\"Name of the deposit method\",\"method_currency\":\"Currency of the deposit method\",\"method_amount\":\"Amount after conversion between base currency and method currency\",\"rejection_message\":\"Rejection message by the admin\"}', 1, '{{site_name}} Billing', NULL, 1, NULL, 0, '2021-11-03 12:00:00', '2024-05-08 07:20:13'),
(6, 'DEPOSIT_REQUEST', 'Deposit - Manual - Requested', 'Deposit Request Submitted Successfully', NULL, '<div>We are pleased to confirm that your deposit request of {{amount}} {{site_currency}} via {{method_name}} has been submitted successfully.</div><div><br></div><div>Below are the details of your deposit:</div><div><br></div><div><b>Amount:</b> {{amount}} {{site_currency}}</div><div><b>Charge:</b> {{charge}} {{site_currency}}</div><div><b>Conversion Rate:</b> 1 {{site_currency}} = {{rate}} {{method_currency}}</div><div><b>Payable:</b> {{method_amount}} {{method_currency}}</div><div><b>Pay via: </b>{{method_name}}</div><div><b>Transaction Number:</b> {{trx}}</div><div><br></div><div>Should you have any questions or require further assistance, please feel free to reach out to our support team. We\'re here to assist you.</div>', 'We are pleased to confirm that your deposit request of {{amount}} {{site_currency}} via {{method_name}} has been submitted successfully.', 'Your deposit request of {{amount}} {{site_currency}} via {{method_name}} submitted successfully.', '{\"trx\":\"Transaction number for the deposit\",\"amount\":\"Amount inserted by the user\",\"charge\":\"Gateway charge set by the admin\",\"rate\":\"Conversion rate between base currency and method currency\",\"method_name\":\"Name of the deposit method\",\"method_currency\":\"Currency of the deposit method\",\"method_amount\":\"Amount after conversion between base currency and method currency\"}', 1, '{{site_name}} Billing', NULL, 1, NULL, 0, '2021-11-03 12:00:00', '2024-04-25 03:27:42'),
(7, 'PASS_RESET_CODE', 'Password - Reset - Code', 'Password Reset', '{{site_name}} Password Reset Code', '<div>We\'ve received a request to reset the password for your account on <b>{{time}}</b>. The request originated from\r\n            the following IP address: <b>{{ip}}</b>, using <b>{{browser}}</b> on <b>{{operating_system}}</b>.\r\n    </div><br>\r\n    <div><span>To proceed with the password reset, please use the following account recovery code</span>: <span><b><font size=\"6\">{{code}}</font></b></span></div><br>\r\n    <div><span>If you did not initiate this password reset request, please disregard this message. Your account security\r\n            remains our top priority, and we advise you to take appropriate action if you suspect any unauthorized\r\n            access to your account.</span></div>', 'To proceed with the password reset, please use the following account recovery code: {{code}}', 'To proceed with the password reset, please use the following account recovery code: {{code}}', '{\"code\":\"Verification code for password reset\",\"ip\":\"IP address of the user\",\"browser\":\"Browser of the user\",\"operating_system\":\"Operating system of the user\",\"time\":\"Time of the request\"}', 1, '{{site_name}} Authentication Center', NULL, 0, NULL, 0, '2021-11-03 12:00:00', '2024-05-08 07:24:57'),
(8, 'PASS_RESET_DONE', 'Password - Reset - Confirmation', 'Password Reset Successful', NULL, '<div><div><span>We are writing to inform you that the password reset for your account was successful. This action was completed at {{time}} from the following browser</span>: <span>{{browser}}</span><span>on {{operating_system}}, with the IP address</span>: <span>{{ip}}</span>.</div><br><div><span>Your account security is our utmost priority, and we are committed to ensuring the safety of your information. If you did not initiate this password reset or notice any suspicious activity on your account, please contact our support team immediately for further assistance.</span></div></div>', 'We are writing to inform you that the password reset for your account was successful.', 'We are writing to inform you that the password reset for your account was successful.', '{\"ip\":\"IP address of the user\",\"browser\":\"Browser of the user\",\"operating_system\":\"Operating system of the user\",\"time\":\"Time of the request\"}', 1, '{{site_name}} Authentication Center', NULL, 1, NULL, 0, '2021-11-03 12:00:00', '2024-04-25 03:27:24'),
(9, 'ADMIN_SUPPORT_REPLY', 'Support - Reply', 'Re: {{ticket_subject}} - Ticket #{{ticket_id}}', '{{site_name}} - Support Ticket Replied', '<div>\r\n    <div><span>Thank you for reaching out to us regarding your support ticket with the subject</span>:\r\n        <span>\"{{ticket_subject}}\"&nbsp;</span><span>and ticket ID</span>: {{ticket_id}}.</div><br>\r\n    <div><span>We have carefully reviewed your inquiry, and we are pleased to provide you with the following\r\n            response</span><span>:</span></div><br>\r\n    <div>{{reply}}</div><br>\r\n    <div><span>If you have any further questions or need additional assistance, please feel free to reply by clicking on\r\n            the following link</span>: <a href=\"{{link}}\" title=\"\" target=\"_blank\">{{link}}</a><span>. This link will take you to\r\n            the ticket thread where you can provide further information or ask for clarification.</span></div><br>\r\n    <div><span>Thank you for your patience and cooperation as we worked to address your concerns.</span></div>\r\n</div>', 'Thank you for reaching out to us regarding your support ticket with the subject: \"{{ticket_subject}}\" and ticket ID: {{ticket_id}}. We have carefully reviewed your inquiry. To check the response, please go to the following link: {{link}}', 'Re: {{ticket_subject}} - Ticket #{{ticket_id}}', '{\"ticket_id\":\"ID of the support ticket\",\"ticket_subject\":\"Subject  of the support ticket\",\"reply\":\"Reply made by the admin\",\"link\":\"URL to view the support ticket\"}', 1, '{{site_name}} Support Team', NULL, 1, NULL, 0, '2021-11-03 12:00:00', '2024-05-08 07:26:06'),
(10, 'EVER_CODE', 'Verification - Email', 'Email Verification Code', NULL, '<div>\r\n    <div><span>Thank you for taking the time to verify your email address with us. Your email verification code\r\n            is</span>: <b><font size=\"6\">{{code}}</font></b></div><br>\r\n    <div><span>Please enter this code in the designated field on our platform to complete the verification\r\n            process.</span></div><br>\r\n    <div><span>If you did not request this verification code, please disregard this email. Your account security is our\r\n            top priority, and we advise you to take appropriate measures if you suspect any unauthorized access.</span>\r\n    </div><br>\r\n    <div><span>If you have any questions or encounter any issues during the verification process, please don\'t hesitate\r\n            to contact our support team for assistance.</span></div><br>\r\n    <div><span>Thank you for choosing us.</span></div>\r\n</div>', '---', '---', '{\"code\":\"Email verification code\"}', 1, '{{site_name}} Verification Center', NULL, 0, NULL, 0, '2021-11-03 12:00:00', '2024-04-25 03:27:12'),
(11, 'SVER_CODE', 'Verification - SMS', 'Verify Your Mobile Number', NULL, '---', 'Your mobile verification code is {{code}}. Please enter this code in the appropriate field to verify your mobile number. If you did not request this code, please ignore this message.', '---', '{\"code\":\"SMS Verification Code\"}', 0, '{{site_name}} Verification Center', NULL, 1, NULL, 0, '2021-11-03 12:00:00', '2024-04-25 03:27:03'),
(12, 'WITHDRAW_APPROVE', 'Withdraw - Approved', 'Withdrawal Confirmation: Your Request Processed Successfully', '{{site_name}} - Withdrawal Request Approved', '<div>We are writing to inform you that your withdrawal request of {{amount}} {{site_currency}} via {{method_name}} has been processed successfully.</div><div><br></div><div>Below are the details of your withdrawal:</div><div><br></div><div><b>Amount:</b> {{amount}} {{site_currency}}</div><div><b>Charge:</b> {{charge}} {{site_currency}}</div><div><b>Conversion Rate:</b> 1 {{site_currency}} = {{rate}} {{method_currency}}</div><div><b>You will receive:</b> {{method_amount}} {{method_currency}}</div><div><b>Via:</b> {{method_name}}</div><div><b>Transaction Number:</b> {{trx}}</div><div><br></div><hr><div><br></div><div><b>Details of Processed Payment:</b></div><div>{{admin_details}}</div><div><br></div><div>Should you have any questions or require further assistance, feel free to reach out to our support team. We\'re here to help.</div>', 'We are writing to inform you that your withdrawal request of {{amount}} {{site_currency}} via {{method_name}} has been processed successfully.', 'Withdrawal Confirmation: Your Request Processed Successfully', '{\"trx\":\"Transaction number for the withdraw\",\"amount\":\"Amount requested by the user\",\"charge\":\"Gateway charge set by the admin\",\"rate\":\"Conversion rate between base currency and method currency\",\"method_name\":\"Name of the withdraw method\",\"method_currency\":\"Currency of the withdraw method\",\"method_amount\":\"Amount after conversion between base currency and method currency\",\"admin_details\":\"Details provided by the admin\"}', 1, '{{site_name}} Finance', NULL, 1, NULL, 0, '2021-11-03 12:00:00', '2024-05-08 07:26:37'),
(13, 'WITHDRAW_REJECT', 'Withdraw - Rejected', 'Withdrawal Request Rejected', '{{site_name}} - Withdrawal Request Rejected', '<div>We regret to inform you that your withdrawal request of {{amount}} {{site_currency}} via {{method_name}} has been rejected.</div><div><br></div><div>Here are the details of your withdrawal:</div><div><br></div><div><b>Amount:</b> {{amount}} {{site_currency}}</div><div><b>Charge:</b> {{charge}} {{site_currency}}</div><div><b>Conversion Rate:</b> 1 {{site_currency}} = {{rate}} {{method_currency}}</div><div><b>Expected Amount:</b> {{method_amount}} {{method_currency}}</div><div><b>Via:</b> {{method_name}}</div><div><b>Transaction Number:</b> {{trx}}</div><div><br></div><hr><div><br></div><div><b>Refund Details:</b></div><div>{{amount}} {{site_currency}} has been refunded to your account, and your current balance is {{post_balance}} {{site_currency}}.</div><div><br></div><hr><div><br></div><div><b>Reason for Rejection:</b></div><div>{{admin_details}}</div><div><br></div><div>If you have any questions or concerns regarding this rejection or need further assistance, please do not hesitate to contact our support team. We apologize for any inconvenience this may have caused.</div>', 'We regret to inform you that your withdrawal request of {{amount}} {{site_currency}} via {{method_name}} has been rejected.', 'Withdrawal Request Rejected', '{\"trx\":\"Transaction number for the withdraw\",\"amount\":\"Amount requested by the user\",\"charge\":\"Gateway charge set by the admin\",\"rate\":\"Conversion rate between base currency and method currency\",\"method_name\":\"Name of the withdraw method\",\"method_currency\":\"Currency of the withdraw method\",\"method_amount\":\"Amount after conversion between base currency and method currency\",\"post_balance\":\"Balance of the user after fter this action\",\"admin_details\":\"Rejection message by the admin\"}', 1, '{{site_name}} Finance', NULL, 1, NULL, 0, '2021-11-03 12:00:00', '2024-05-08 07:26:55'),
(14, 'WITHDRAW_REQUEST', 'Withdraw - Requested', 'Withdrawal Request Confirmation', '{{site_name}} - Requested for withdrawal', '<div>We are pleased to inform you that your withdrawal request of {{amount}} {{site_currency}} via {{method_name}} has been submitted successfully.</div><div><br></div><div>Here are the details of your withdrawal:</div><div><br></div><div><b>Amount:</b> {{amount}} {{site_currency}}</div><div><b>Charge:</b> {{charge}} {{site_currency}}</div><div><b>Conversion Rate:</b> 1 {{site_currency}} = {{rate}} {{method_currency}}</div><div><b>Expected Amount:</b> {{method_amount}} {{method_currency}}</div><div><b>Via:</b> {{method_name}}</div><div><b>Transaction Number:</b> {{trx}}</div><div><br></div><div>Your current balance is {{post_balance}} {{site_currency}}.</div><div><br></div><div>Should you have any questions or require further assistance, feel free to reach out to our support team. We\'re here to help.</div>', 'We are pleased to inform you that your withdrawal request of {{amount}} {{site_currency}} via {{method_name}} has been submitted successfully.', 'Withdrawal request submitted successfully', '{\"trx\":\"Transaction number for the withdraw\",\"amount\":\"Amount requested by the user\",\"charge\":\"Gateway charge set by the admin\",\"rate\":\"Conversion rate between base currency and method currency\",\"method_name\":\"Name of the withdraw method\",\"method_currency\":\"Currency of the withdraw method\",\"method_amount\":\"Amount after conversion between base currency and method currency\",\"post_balance\":\"Balance of the user after fter this transaction\"}', 1, '{{site_name}} Finance', NULL, 1, NULL, 0, '2021-11-03 12:00:00', '2024-05-08 07:27:20'),
(15, 'DEFAULT', 'Default Template', '{{subject}}', '{{subject}}', '{{message}}', '{{message}}', '{{message}}', '{\"subject\":\"Subject\",\"message\":\"Message\"}', 1, NULL, NULL, 1, NULL, 1, '2019-09-14 13:14:22', '2024-05-16 01:32:53'),
(16, 'KYC_APPROVE', 'KYC Approved', 'KYC Details has been approved', '{{site_name}} - KYC Approved', '<div><div><span>We are pleased to inform you that your Know Your Customer (KYC) information has been successfully reviewed and approved. This means that you are now eligible to conduct any payout operations within our system.</span></div><br><div><span>Your commitment to completing the KYC process promptly is greatly appreciated, as it helps us ensure the security and integrity of our platform for all users.</span></div><br><div><span>With your KYC verification now complete, you can proceed with confidence to carry out any payout transactions you require. Should you encounter any issues or have any questions along the way, please don\'t hesitate to reach out to our support team. We\'re here to assist you every step of the way.</span></div><br><div><span>Thank you once again for choosing {{site_name}} and for your cooperation in this matter.</span></div></div>', 'We are pleased to inform you that your Know Your Customer (KYC) information has been successfully reviewed and approved. This means that you are now eligible to conduct any payout operations within our system.', 'Your  Know Your Customer (KYC) information has been approved successfully', '[]', 1, '{{site_name}} Verification Center', NULL, 1, NULL, 0, NULL, '2024-05-08 07:23:57'),
(17, 'KYC_REJECT', 'KYC Rejected', 'KYC has been rejected', '{{site_name}} - KYC Rejected', '<div><div><span>We regret to inform you that the Know Your Customer (KYC) information provided has been reviewed and unfortunately, it has not met our verification standards. As a result, we are unable to approve your KYC submission at this time.</span></div><br><div><span>We understand that this news may be disappointing, and we want to assure you that we take these matters seriously to maintain the security and integrity of our platform.</span></div><br><div><span>Reasons for rejection may include discrepancies or incomplete information in the documentation provided. If you believe there has been a misunderstanding or if you would like further clarification on why your KYC was rejected, please don\'t hesitate to contact our support team.</span></div><br><div><span>We encourage you to review your submitted information and ensure that all details are accurate and up-to-date. Once any necessary adjustments have been made, you are welcome to resubmit your KYC information for review.</span></div><br><div><span>We apologize for any inconvenience this may cause and appreciate your understanding and cooperation in this matter.</span></div><br><div>Rejection Reason:</div><div>{{reason}}</div><div><br></div><div><span>Thank you for your continued support and patience.</span></div></div>', 'We regret to inform you that the Know Your Customer (KYC) information provided has been reviewed and unfortunately, it has not met our verification standards. As a result, we are unable to approve your KYC submission at this time. We encourage you to review your submitted information and ensure that all details are accurate and up-to-date. Once any necessary adjustments have been made, you are welcome to resubmit your KYC information for review.', 'Your  Know Your Customer (KYC) information has been rejected', '{\"reason\":\"Rejection Reason\"}', 1, '{{site_name}} Verification Center', NULL, 1, NULL, 0, NULL, '2024-05-08 07:24:13'),
(18, 'BID_PLACED', 'Bid Placed', 'Bid has been placd successfully', '{{site_name}} - Bid placed successfully', '<div>Great news! A new freelancer has submitted a bid for your job [<b>{{title}}]</b>.</div><div><br></div><div>Freelancer: {{freelancer}}</div><div>Bid Amount: {{amount}}</div><div>Budget Type: {{budget_type}}</div>\r\n<div>Estimate Time: {{estimate}}</div><div><br></div><div>To review the bid and communicate with the freelancer.</div>', 'Great news! {{freelancer}} has submitted a bid for your job \'{{title}}\'.', 'A new freelancer has bid on your job {{title}}.', '{\"amount\":\"Bid budget\",\"title\":\"Job title\",\"freelancer\":\"Bidded freelancer name\",\"budget_type\":\"Type of bid budget\"}', 1, '{{site_name}} Verification Center', NULL, 1, NULL, 0, NULL, '2025-03-10 00:21:47'),
(19, 'BID_REJECTED', 'Bid rejected', 'Bid has been rejected successfully', '{{site_name}} - Bid rejected successfully', '<div>Oops! Your bid for {{title}} wasn’t selected this time. Don’t lose heart—there are plenty of opportunities ahead!</div><div>💼 Budget Type: {{budget_type}}&nbsp;&nbsp;</div><div>💰 Your Bid: {{bid_amount}}</div><div><br></div><div>Don\'t give up—more opportunities are just around the corner!</div>', 'Your bid for {{title}} wasn’t selected this time. Don’t lose heart—there are plenty of opportunities ahead!\"', 'Rejected bid on your {{title}}.', '{\"title\":\"Job post title\",\"budget_type\":\"Job budget type\",\"bid_amount\":\"Bid Amount for project\"}', 1, '{{site_name}} Verification Center', NULL, 1, NULL, 0, NULL, '2025-03-10 00:20:44'),
(20, 'BID_ACCEPTED', 'Bid accepted', 'Bid has been accepted successfully', '{{site_name}} - Bid accepted successfully', '<div>Congratulations! Your bid for the project titled [{{title}}] has been accepted by {{buyer}}.</div><div>💼 Budget Type: {{budget_type}}</div><div>💰Your Bid Amount: {{bid_amount}}</div><div>Estimated \r\n🕒 Time: {{estimated_time}}</div><div> ⏲️Assigned On: {{assigned_at}}</div><div><br></div><div>Best of luck with the project!</div><div><br></div>', 'Congratulations! Your bid for the project titled \"[{{title}}]\" has been accepted by {{buyer}}.', 'Accepted bid on your {{title}}.', '{\"title\":\"Job post title\",\"budget_type\":\"Job budget type\",\"bid_amount\":\"Bid Amount for project\", \"buyer\":\"Job owner\", \"estimated_time\":\"Freelancer estimated time\", \"assigned_at\":\"Project assigned date by buyer\"}', 1, '{{site_name}} Verification Center', NULL, 1, NULL, 0, NULL, '2025-03-10 00:21:17'),
(21, 'FREELANCER_INVITATION', 'Freelancer Invitation', 'Freelancer has been invitation successfully', '{{site_name}} - Freelancer invitation successfully', 'Don\'t miss out! <b>{{buyer}}</b> has invited you to work on [<b>{{active_post}}</b>] projects. Accept the invitation now to secure these opportunities.\r\nVisit job page <b>{{post_page}}</b> to explore opportunities.', 'Don\'t miss out! {{buyer}} has invitation.Visit job page {{post_page}} to explore opportunities.', 'Explore {{buyer}} invitation.', '{\"buyer\":\"Job owner name\", \"active_post\":\"Buyer active post\", \"post_page\":\"Buyer jobs\"}', 1, '{{site_name}} Verification Center', NULL, 1, NULL, 0, NULL, '2024-12-04 05:38:30'),
(22, 'BID_WITHRAWN', 'Freelancer bid withdrawn', 'Freelancer has been withdrawn bid successfully', '{{site_name}} - Freelancer withdrawn successfully', 'Opps!The freelancer <strong>{{freelancer}}</strong> has withdrawn their bid for the job:<strong>{{job}}</strong>.', 'Opps!The freelancer <strong>{{freelancer}}</strong> has withdrawn their bid for the job:<strong>{{job}}</strong>.', '{{freelancer}} withdrawn their bid.', '{\"freelancer\":\"Bid freelancer name\", \"job\":\"Which job was bid the freelancer\"}', 1, '{{site_name}} Verification Center', NULL, 1, NULL, 0, NULL, '2024-12-04 05:38:30'),
(23, 'JOB_APPROVED', 'Job-Post-Approved', 'Job post has been approved successfully', '{{site_name}} - Job post has been approved successfully', 'Congratulations! Job post of <strong>{{job}}</strong> has has been approved.&nbsp;', 'Congratulations!Job post of <strong>{{job}}</strong> has has been approved. </strong>.', '[{{job}}] has been approved.', '{\"job\":\"Approved job title\"}', 1, '{{site_name}} Verification Center', NULL, 1, NULL, 0, NULL, '2025-03-10 00:19:35'),
(24, 'JOB_REJECTED', 'Job-Post-Rejcted', 'Job post has been rejected successfully.', '{{site_name}} - Job post has been rejected.', 'Opps! Job post of [{{job}}] has has been rejected.&nbsp;<strong>Reason : {{reason}}</strong>.', 'Opps!Job post of [{{job}}]</strong> has has been rejected.<strong>Reason : {{reason}}</strong>. \r\nthe', '[{{job}}] has been rejected.', '{\"job\":\"Rejected job title\", \"reason\":\"Reject reason of this job\"}', 1, '{{site_name}} Verification Center', NULL, 1, NULL, 0, NULL, '2025-03-10 00:20:04'),
(25, 'PROJECT_BUYER_REVIEW', 'Project-Buyer-Review', 'Project successfully upload for Review', '{{site_name}}: Project successfully upload for Review', '<div>\r\n    Your project <b>{{job}}</b> has been submitted by freelancer <b>{{freelancer}}</b>.\r\n</div>\r\n<div><br></div>\r\n<div><b>Comments:</b> \"{{comments}}\"</div>\r\n<div><br></div>\r\n<hr>\r\n<div><br></div>\r\n<div><b>Next Steps:</b> Review the project and take action.</div>\r\n<div>Click below for full details:</div>\r\n<div><br></div>\r\n<div><a href=\"{{link}}\" style=\"background-color: #28a745; color: #ffffff; text-decoration: none; padding: 10px 20px; border-radius: 5px; font-weight: bold;\">Review & Take Action</a>\r\n</div>\r\n', 'Project {{job}} has been submitted by {{freelancer}}. Comment: {{comments}}. Review and take action here: {{link}}', '[{{job_title}}] Completed project uploaded for review successfully.\r\n', '{\"job\":\"Reviewing job title\", \"message\":\"Reviewing  reason of this job\", \"comments\":\"comment of this done project\", \"link\":\"Review Here, Detail of completed project & Make review\"}', 1, '{{site_name}} Verification Center', NULL, 1, NULL, 0, NULL, '2024-12-04 05:38:30'),
(26, 'PROJECT_COMPLETED', 'Project-Completed', 'Project Completed Successfully', '{{site_name}}: Project Completed Successfully', '<h2 style=\"margin: 0; font-size: 24px; font-weight: 600; padding: 10px; text-align: center;\">Project Completed Successfully</h2>\r\n <div style=\"padding: 24px;\">\r\n <div style=\"background-color: #f8f9fa; border-radius: 8px; padding: 16px; margin-bottom: 16px;\">\r\n<table style=\"width: 100%; border-collapse: separate; border-spacing: 0 12px;\"><tbody><tr><td style=\"font-weight: 600; color: #6c757d; width: 40%;\">Job Title</td><td style=\"color: #212529;\">{{job}}</td></tr> <tr><td style=\"font-weight: 600; color: #6c757d;\">Income</td>\r\n<td style=\"color: #28a745; font-weight: 600;\">{{income}}</td></tr><tr><td style=\"font-weight: 600; color: #6c757d;\">Charge</td><td style=\"color: #212529;\">{{charge}}</td></tr><tr><td style=\"font-weight: 600; color: #6c757d;\">Buyer</td><td style=\"color: #212529;\">{{buyer}}</td></tr><tr><td style=\"font-weight: 600; color: #6c757d;\">Rating</td> <td style=\"color: #ffc107;\"><span style=\"margin-left: 8px;\">{{rating}} / 5</span></td></tr></tbody></table> </div><div style=\"background-color: #e9ecef; border-radius: 8px; padding: 16px;\"><h3 style=\"margin: 0 0 12px 0; font-size: 18px; color: #495057;\">Buyer Review</h3> <p style=\"margin: 0; color: #212529; font-style: italic;\">\"{{review}}\"</p></div> </div>', 'Project \'{{job}}\' Completed by {{buyer}}.', 'Project \'{{job}}\' Completed by {{buyer}}.\r\n', '{\"job\":\"Job title\", \"review\":\"Review for talent\", \"income\":\"Total income  of this project\", \"charge\":\"Charges of this project\", \"rating\":\"Buyer though for this freelancer\", \"review\":\"Buyer review for this freelancer about done project\"}', 1, '{{site_name}} Verification Center', NULL, 1, NULL, 0, NULL, '2024-12-09 02:21:05'),
(27, 'PROJECT_REPORTED', 'Project-Reported', 'Project Reported Successfully', '{{site_name}}: Project Reported Successfully', '  <p><strong>Project Reported:</strong> <span style=\"color: #d9534f;\">{{job}}</span></p><p><strong>Reported By:</strong> {{buyer}}</p><p><strong>Reason:</strong>{{reason}}.</p>', 'Project Reported by {{buyer}}.', 'Project Reported by {{buyer}}.\r\n', '{\"job\":\"Job title\", \"reason\":\"Reported reason\", \"buyer\":\"Reported by this buyer\"}', 1, '{{site_name}} Verification Center', NULL, 1, NULL, 0, NULL, '2024-12-09 02:21:05'),
(28, 'REPORTED_PROJECT_COMPLETED', 'Reported-Project-Completed', 'Reported Project Completed Successfully', '{{site_name}}: Reported Project Completed Successfully', '<h2 style=\"margin: 0; font-size: 24px; font-weight: 600; padding: 10px; text-align: center;\">\r\n    Reported Project Successfully Resolved\r\n</h2>\r\n<div style=\"padding: 24px;\">\r\n    <p style=\"margin-bottom: 16px; text-align: center; font-size: 16px; color: #6c757d;\">\r\n        The reported project has been successfully resolved and marked as completed.\r\n    </p>\r\n    <div style=\"background-color: #f8f9fa; border-radius: 8px; padding: 16px; margin-bottom: 16px;\">\r\n        <table style=\"width: 100%; border-collapse: separate; border-spacing: 0 12px;\">\r\n            <tbody>\r\n                <tr>\r\n                    <td style=\"font-weight: 600; color: #6c757d; width: 40%;\">Job Title</td>\r\n                    <td style=\"color: #212529;\">{{job}}</td>\r\n                </tr>\r\n                <tr>\r\n                    <td style=\"font-weight: 600; color: #6c757d;\">Total Income</td>\r\n                    <td style=\"color: #28a745; font-weight: 600;\">{{income}}</td>\r\n                </tr>\r\n                <tr>\r\n                    <td style=\"font-weight: 600; color: #6c757d;\">Service Charge</td>\r\n                    <td style=\"color: #212529;\">{{charge}}</td>\r\n                </tr>\r\n                <tr>\r\n                    <td style=\"font-weight: 600; color: #6c757d;\">Buyer</td>\r\n                    <td style=\"color: #212529;\">{{buyer}}</td>\r\n                </tr>\r\n            </tbody>\r\n        </table>\r\n    </div>\r\n</div>\r\n', 'Reported Project \'{{job}}\' Completed by {{buyer}}.', 'Reported Project \'{{job}}\' Completed by {{buyer}}.\r\n', '{\"job\":\"Job title\", \"review\":\"Review for talent\", \"income\":\"Total income  of this project\", \"charge\":\"Charges of this project\"}', 1, '{{site_name}} Verification Center', NULL, 1, NULL, 0, NULL, '2024-12-09 02:21:05'),
(29, 'REPORTED_PROJECT_REJECTED', 'Reported-Project-Rejected', 'Reported Project Rejected Successfully', '{{site_name}}: Reported Project Rejected Successfully', '<div style=\"max-width: 600px; margin: 20px auto; background-color: #fff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); overflow: hidden;\">\r\n        \r\n        <!-- Header -->\r\n        <div style=\"background-color: #dc3545; color: #fff; text-align: center; padding: 15px 0; font-size: 22px; font-weight: bold;\">\r\n            Reported Project Resolved & Rejected\r\n        </div>\r\n\r\n        <!-- Notification Message -->\r\n        <div style=\"padding: 20px; text-align: center; font-size: 16px; color: #6c757d;\">\r\n            <p style=\"margin: 0;\">\r\n                The reported project has been successfully resolved and marked as <strong>Rejected</strong>.\r\n            </p>\r\n        </div>\r\n\r\n        <!-- Project Details -->\r\n        <div style=\"background-color: #f8f9fa; border-radius: 8px; padding: 16px; margin: 20px;\">\r\n            <table style=\"width: 100%; border-collapse: separate; border-spacing: 0 12px;\">\r\n                <tr>\r\n                    <td style=\"font-weight: 600; color: #6c757d; width: 40%; padding: 6px 0;\">Job Title</td>\r\n                    <td style=\"color: #212529; padding: 6px 0;\">{{job}}</td>\r\n                </tr>\r\n                <tr>\r\n                    <td style=\"font-weight: 600; color: #6c757d; padding: 6px 0;\">Buyer</td>\r\n                    <td style=\"color: #212529; padding: 6px 0;\">{{buyer}}</td>\r\n                </tr>\r\n                <tr>\r\n                    <td style=\"font-weight: 600; color: #6c757d; padding: 6px 0;\">Freelancer</td>\r\n                    <td style=\"color: #212529; padding: 6px 0;\">{{freelancer}}</td>\r\n                </tr>\r\n                <tr>\r\n                    <td style=\"font-weight: 600; color: #6c757d; padding: 6px 0;\">Clinet Escrow Amount</td>\r\n                    <td style=\"color: #212529; padding: 6px 0;\">{{escrow_amount}}</td>\r\n                </tr>\r\n            </table>\r\n        </div>\r\n    </div>', 'Reported Project \'{{job}}\' has been rejected.', 'Reported Project \'{{job}}\' has been rejected.\r\n', '{\"job\":\"Job title\", \"buyer\":\"Job owner or buyer\", \"freelancer\":\"Freelancer assigned to this job\", \"escrow_amount\": \"Escrow- Amount held in escrow for secure and transparent payment\"}', 1, '{{site_name}} Verification Center', NULL, 1, NULL, 0, NULL, '2024-12-09 02:21:05'),
(30, 'TRIAL_TASK_ASSIGN', 'Trial Task Assigned', 'You Have Received a Trial Task', '{{site_name}} - Trial Task Assigned', '<div>You have been assigned a paid trial task by <b>{{buyer}}</b>.</div>\r\n  <div><br></div>\r\n  <div><b>Task Title:</b> {{title}}</div>\r\n  <div><b>Task Amount:</b> {{amount}} {{site_currency}}</div>\r\n  <div><br></div>\r\n  <div>Please review the task details and start working within the deadline.</div>\r\n  <div><br></div>\r\n  <div>Thank you for being part of {{site_name}}.</div>', 'You have received a paid trial task: {{title}} ({{amount}} {{site_currency}}).', 'New trial task assigned: {{title}} ({{amount}} {{site_currency}}).', '{\"buyer\":\"Buyer username\",\"title\":\"Trial task title\",\"deadline\":\"Deadline of the task\",\"amount\":\"Trial task amount\"}', 1, '{{site_name}}', NULL, 0, NULL, 1, '2026-01-03 08:50:33', '2026-01-03 08:50:33'),
(31, 'TRIAL_TASK_ACCEPTED', 'Trial Task Accepted', 'Trial Task Accepted by Freelancer', '{{site_name}} - Trial Task Accepted', '<div>Your trial task has been accepted by <b>{{freelancer}}</b>.</div>\r\n  <div><br></div>\r\n  <div><b>Task Title:</b> {{title}}</div>\r\n  <div><b>Task Amount:</b> {{amount}} {{site_currency}}</div>\r\n  <div><br></div>\r\n  <div>Your assign trial task is accepted by the freelancer. </div>\r\n  <div><br></div>\r\n  <div>Thank you for using {{site_name}}.</div>', 'Your trial task \"{{title}}\" has been accepted by {{freelancer}}.', 'Trial task accepted: {{title}} by {{freelancer}}.', '{\"freelancer\":\"Freelancer username\",\"title\":\"Trial task title\",\"amount\":\"Trial task amount\"}', 1, '{{site_name}}', NULL, 0, NULL, 1, '2026-01-03 08:50:33', '2026-01-03 08:50:33'),
(32, 'TRIAL_TASK_SUBMITTED', 'Trial Task Submitted', 'Trial Task Submitted by Freelancer', '{{site_name}} - Trial Task Submitted', '<div>Your trial task has been submitted by <b>{{freelancer}}</b>.</div>\r\n  <div><br></div>\r\n  <div><b>Task Title:</b> {{title}}</div>\r\n  <div><b>Task Amount:</b> {{amount}} {{site_currency}}</div>\r\n  <div><br></div>\r\n  <div>Please review the submission and take the next action.</div>\r\n  <div><br></div>\r\n  <div>Thank you for using {{site_name}}.</div>', 'Your trial task \"{{title}}\" has been submitted by {{freelancer}}.', 'Trial task submitted: {{title}} by {{freelancer}}.', '{\"freelancer\":\"Freelancer username\",\"title\":\"Trial task title\",\"amount\":\"Trial task amount\"}', 1, '{{site_name}}', NULL, 0, NULL, 1, '2026-01-03 08:50:33', '2026-01-03 08:50:33'),
(33, 'TRIAL_TASK_FINISHED', 'Trial Task Finished', 'Your Trial Task Has Been Completed', '{{site_name}} - Trial Task Finished', '<div>Hello {{freelancer}},</div>\r\n  <div><br></div>\r\n  <div>Congratulations! Your trial task has been successfully completed and approved.</div>\r\n  <div><br></div>\r\n  <div><b>Task Title:</b> {{job}}</div>\r\n  <div><b>Buyer:</b> {{buyer}}</div>\r\n  <div><b>Your Earnings:</b> {{income}} {{site_currency}}</div>\r\n  <div><b>Platform Charge:</b> {{charge}} {{site_currency}}</div>\r\n  <div><br></div>\r\n  <div>The payment has been released to your account.</div>\r\n  <div><br></div>\r\n  <div>Thank you for working with {{site_name}}. We hope this leads to more opportunities!</div>', 'Hello {{freelancer}}, your trial task \"{{job}}\" has been completed. You earned {{income}} {{site_currency}}.', 'Trial task finished for {{freelancer}}: {{job}} | Earned {{income}} {{site_currency}}.', '{\"freelancer\":\"Freelancer full name\",\"job\":\"Trial task title\",\"income\":\"Freelancer final income\",\"charge\":\"Platform charge\",\"buyer\":\"Buyer full name\"}', 1, '{{site_name}}', NULL, 0, NULL, 1, '2026-01-03 08:50:33', '2026-01-03 08:50:33'),
(34, 'TRIAL_TASK_CANCELED', 'Trial Task Canceled', 'Your Trial Task Has Been Canceled', '{{site_name}} - Trial Task Canceled', '<div>Your trial task has been canceled by <b>{{buyer}}</b>.</div>\r\n  <div><br></div>\r\n  <div><b>Task Title:</b> {{job}}</div>\r\n  <div><b>Reason:</b> {{reason}}</div>\r\n  <div><br></div>\r\n  <div>If you have any questions, please contact support.</div>\r\n  <div><br></div>\r\n  <div>Thank you for using {{site_name}}.</div>', 'Your trial task \"{{job}}\" was canceled by {{buyer}}. Reason: {{reason}}.', 'Trial task canceled: {{job}}', '{\"buyer\":\"Buyer name\",\"job\":\"Trial task title\",\"reason\":\"Cancellation reason\"}', 1, '{{site_name}}', NULL, 0, NULL, 1, '2026-01-03 08:50:33', '2026-01-03 08:50:33'),
(35, 'REPORTED_PROJECT_PARTIAL_COMPLETED', 'Reported Project Partial Completed', 'Your Project Was Partially Completed', '{{site_name}} - Project Partially Completed', '<div>Your reported project has been <b>partially completed</b>.</div>\r\n  <div><br></div>\r\n  <div><b>Job Title:</b> {{job}}</div>\r\n  <div><b>Buyer:</b> {{buyer}}</div>\r\n  <div><b>Approved Percentage:</b> {{percent}}%</div>\r\n  <div><b>Your Income:</b> {{income}}</div>\r\n  <div><br></div>\r\n  <div>If you have any questions, please contact support.</div>\r\n  <div><br></div>\r\n  <div>Thank you for using {{site_name}}.</div>', 'Your project \"{{job}}\" was partially completed ({{percent}}%). Income: {{income}}.', 'Project partially completed ({{percent}}%): {{job}}', '{\"job\":\"Project title\",\"buyer\":\"Buyer name\",\"income\":\"Freelancer income\",\"percent\":\"Approved percentage\"}', 1, '{{site_name}}', NULL, 0, NULL, 1, '2026-01-03 08:50:33', '2026-01-03 08:50:33'),
(36, 'REPORTED_PROJECT_PARTIAL_REFUND', 'Reported Project Partial Refund', 'Partial Refund Issued for Your Project', '{{site_name}} - Partial Refund Issued', '<div>Your reported project has been <b>partially refunded</b>.</div>\r\n  <div><br></div>\r\n  <div><b>Job Title:</b> {{job}}</div>\r\n  <div><b>Freelancer:</b> {{freelancer}}</div>\r\n  <div><b>Refund Percentage:</b> {{percent}}%</div>\r\n  <div><b>Refund Amount:</b> {{refund}}</div>\r\n  <div><br></div>\r\n  <div>The refund has been credited to your balance.</div>\r\n  <div><br></div>\r\n  <div>Thank you for using {{site_name}}.</div>', 'You received a {{percent}}% refund for \"{{job}}\". Amount: {{refund}}.', 'Partial refund ({{percent}}%): {{job}}', '{\"job\":\"Project title\",\"freelancer\":\"Freelancer name\",\"refund\":\"Refund amount\",\"percent\":\"Refund percentage\"}', 1, '{{site_name}}', NULL, 0, NULL, 1, '2026-01-03 08:50:33', '2026-01-03 08:50:33');

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tempname` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'template name',
  `secs` text COLLATE utf8mb4_unicode_ci,
  `seo_content` text COLLATE utf8mb4_unicode_ci,
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `name`, `slug`, `tempname`, `secs`, `seo_content`, `is_default`, `created_at`, `updated_at`) VALUES
(1, 'HOME', '/', 'templates.basic.', '[\"category\",\"how_work\",\"account\",\"why_choose\",\"find_task\",\"facility\",\"completion_work\",\"testimonial\",\"top_freelancer\",\"subscribe\",\"faq\",\"blog\"]', NULL, 1, '2020-07-11 06:23:58', '2025-02-01 06:54:40'),
(3, 'Find Job', 'freelance-jobs', 'templates.basic.', '', NULL, 1, '2020-10-22 01:14:53', '2024-11-19 23:15:47'),
(4, 'Find Talent', 'talents', 'templates.basic.', '', NULL, 1, '2024-11-18 23:47:04', '2024-11-19 01:03:45'),
(5, 'Blog', 'blog', 'templates.basic.', NULL, NULL, 1, '2020-10-22 01:14:43', '2020-10-22 01:14:43'),
(6, 'Contact', 'contact', 'templates.basic.', '[\"support\",\"faq\"]', NULL, 1, '2020-10-22 01:14:53', '2024-11-19 23:15:47'),
(10, 'About', 'about', 'templates.basic.', '[\"account\",\"about\",\"why_choose\",\"completion_work\",\"testimonial\",\"top_freelancer\",\"support\"]', NULL, 0, '2024-11-18 23:47:04', '2025-03-17 00:38:14');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `token` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `portfolios`
--

CREATE TABLE `portfolios` (
  `id` int NOT NULL,
  `user_id` int NOT NULL DEFAULT '0',
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `skill_ids` text COLLATE utf8mb4_unicode_ci,
  `description` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` int NOT NULL,
  `bid_id` int NOT NULL DEFAULT '0',
  `job_id` int NOT NULL DEFAULT '0',
  `user_id` int NOT NULL DEFAULT '0' COMMENT 'Talent / Freelancer id',
  `buyer_id` int NOT NULL DEFAULT '0',
  `escrow_amount` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `project_file` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `comments` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1=>Running, 2=> Complete, 3=>Failed, 4=>''Reviewing by buyer'', 5=> ''Reported''',
  `report_reason` text COLLATE utf8mb4_unicode_ci,
  `reject_reason` text COLLATE utf8mb4_unicode_ci,
  `partial_approve_reason` text COLLATE utf8mb4_unicode_ci,
  `uploaded_at` datetime DEFAULT NULL,
  `upload_count` int NOT NULL DEFAULT '0',
  `blocked_review` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` bigint NOT NULL,
  `user_id` int NOT NULL DEFAULT '0',
  `buyer_id` int NOT NULL DEFAULT '0' COMMENT 'How given review',
  `project_id` int NOT NULL DEFAULT '0',
  `rating` int NOT NULL DEFAULT '0' COMMENT 'Buyer give this rating',
  `review` text COLLATE utf8mb4_unicode_ci COMMENT 'Buyer give this review',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `skills`
--

CREATE TABLE `skills` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(40) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `skill_user`
--

CREATE TABLE `skill_user` (
  `id` bigint NOT NULL,
  `user_id` int NOT NULL DEFAULT '0',
  `skill_id` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subcategories`
--

CREATE TABLE `subcategories` (
  `id` bigint UNSIGNED NOT NULL,
  `category_id` bigint UNSIGNED NOT NULL DEFAULT '0',
  `name` varchar(40) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscribers`
--

CREATE TABLE `subscribers` (
  `id` bigint UNSIGNED NOT NULL,
  `email` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `support_attachments`
--

CREATE TABLE `support_attachments` (
  `id` bigint UNSIGNED NOT NULL,
  `support_message_id` int UNSIGNED NOT NULL DEFAULT '0',
  `attachment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `support_messages`
--

CREATE TABLE `support_messages` (
  `id` bigint UNSIGNED NOT NULL,
  `support_ticket_id` int UNSIGNED NOT NULL DEFAULT '0',
  `admin_id` int UNSIGNED NOT NULL DEFAULT '0',
  `message` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `support_tickets`
--

CREATE TABLE `support_tickets` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` int DEFAULT '0',
  `buyer_id` int NOT NULL DEFAULT '0',
  `name` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ticket` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0: Open, 1: Answered, 2: Replied, 3: Closed',
  `priority` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1 = Low, 2 = medium, 3 = heigh',
  `last_reply` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL DEFAULT '0',
  `buyer_id` int NOT NULL DEFAULT '0',
  `project_id` int NOT NULL DEFAULT '0' COMMENT 'Assign project id',
  `task_id` int NOT NULL DEFAULT '0',
  `amount` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `charge` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `post_balance` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `trx_type` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trx` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `details` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remark` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trial_tasks`
--

CREATE TABLE `trial_tasks` (
  `id` int NOT NULL,
  `user_id` int NOT NULL DEFAULT '0',
  `buyer_id` int NOT NULL DEFAULT '0',
  `job_id` int NOT NULL DEFAULT '0',
  `bid_id` int NOT NULL DEFAULT '0',
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `escrow_amount` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `deadline` date DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `comments` text COLLATE utf8mb4_unicode_ci,
  `task_file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `upload_count` int NOT NULL DEFAULT '0',
  `uploaded_at` datetime DEFAULT NULL,
  `report_reason` text COLLATE utf8mb4_unicode_ci,
  `cancel_reason` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `update_logs`
--

CREATE TABLE `update_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `version` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `update_log` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `badge_setting_id` int NOT NULL DEFAULT '0',
  `firstname` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lastname` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `username` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dial_code` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `balance` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country_code` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0: banned, 1: active',
  `tagline` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kyc_data` text COLLATE utf8mb4_unicode_ci,
  `kyc_rejection_reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kv` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0: KYC Unverified, 2: KYC pending, 1: KYC verified',
  `ev` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0: email unverified, 1: email verified',
  `sv` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0: mobile unverified, 1: mobile verified',
  `language` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `profile_complete` tinyint(1) NOT NULL DEFAULT '0',
  `ver_code` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'stores verification code',
  `ver_code_send_at` datetime DEFAULT NULL COMMENT 'verification send time',
  `ts` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0: 2fa off, 1: 2fa on',
  `tv` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0: 2fa unverified, 1: 2fa verified',
  `tsc` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ban_reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `work_profile_complete` tinyint(1) NOT NULL DEFAULT '0',
  `step` tinyint(1) NOT NULL DEFAULT '0',
  `about` text COLLATE utf8mb4_unicode_ci,
  `avg_rating` decimal(5,2) NOT NULL DEFAULT '0.00',
  `earning` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_logins`
--

CREATE TABLE `user_logins` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL DEFAULT '0',
  `buyer_id` int NOT NULL DEFAULT '0',
  `user_ip` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country_code` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `longitude` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `browser` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `os` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `withdrawals`
--

CREATE TABLE `withdrawals` (
  `id` bigint UNSIGNED NOT NULL,
  `method_id` int UNSIGNED NOT NULL DEFAULT '0',
  `user_id` int UNSIGNED NOT NULL DEFAULT '0',
  `buyer_id` int NOT NULL DEFAULT '0',
  `amount` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `currency` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rate` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `charge` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `trx` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `final_amount` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `after_charge` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `withdraw_information` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1=>success, 2=>pending, 3=>cancel,  ',
  `admin_feedback` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `withdraw_methods`
--

CREATE TABLE `withdraw_methods` (
  `id` bigint UNSIGNED NOT NULL,
  `form_id` int UNSIGNED NOT NULL DEFAULT '0',
  `name` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `min_limit` decimal(28,8) DEFAULT '0.00000000',
  `max_limit` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `fixed_charge` decimal(28,8) DEFAULT '0.00000000',
  `rate` decimal(28,8) DEFAULT '0.00000000',
  `percent_charge` decimal(5,2) DEFAULT NULL,
  `currency` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`,`username`);

--
-- Indexes for table `admin_notifications`
--
ALTER TABLE `admin_notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin_password_resets`
--
ALTER TABLE `admin_password_resets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `badge_settings`
--
ALTER TABLE `badge_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bids`
--
ALTER TABLE `bids`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `buyers`
--
ALTER TABLE `buyers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `buyer_password_resets`
--
ALTER TABLE `buyer_password_resets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `buyer_reviews`
--
ALTER TABLE `buyer_reviews`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `charges`
--
ALTER TABLE `charges`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `conversations`
--
ALTER TABLE `conversations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `deposits`
--
ALTER TABLE `deposits`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `device_tokens`
--
ALTER TABLE `device_tokens`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `education`
--
ALTER TABLE `education`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `extensions`
--
ALTER TABLE `extensions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `forms`
--
ALTER TABLE `forms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `frontends`
--
ALTER TABLE `frontends`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gateways`
--
ALTER TABLE `gateways`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `gateway_currencies`
--
ALTER TABLE `gateway_currencies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `general_settings`
--
ALTER TABLE `general_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `job_skills`
--
ALTER TABLE `job_skills`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notification_logs`
--
ALTER TABLE `notification_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notification_templates`
--
ALTER TABLE `notification_templates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `portfolios`
--
ALTER TABLE `portfolios`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `skills`
--
ALTER TABLE `skills`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `skill_user`
--
ALTER TABLE `skill_user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subcategories`
--
ALTER TABLE `subcategories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subscribers`
--
ALTER TABLE `subscribers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `support_attachments`
--
ALTER TABLE `support_attachments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `support_messages`
--
ALTER TABLE `support_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `support_tickets`
--
ALTER TABLE `support_tickets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `trial_tasks`
--
ALTER TABLE `trial_tasks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `update_logs`
--
ALTER TABLE `update_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`,`email`);

--
-- Indexes for table `user_logins`
--
ALTER TABLE `user_logins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `withdrawals`
--
ALTER TABLE `withdrawals`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `withdraw_methods`
--
ALTER TABLE `withdraw_methods`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `admin_notifications`
--
ALTER TABLE `admin_notifications`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `admin_password_resets`
--
ALTER TABLE `admin_password_resets`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `badge_settings`
--
ALTER TABLE `badge_settings`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bids`
--
ALTER TABLE `bids`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `buyers`
--
ALTER TABLE `buyers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `buyer_password_resets`
--
ALTER TABLE `buyer_password_resets`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `buyer_reviews`
--
ALTER TABLE `buyer_reviews`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `charges`
--
ALTER TABLE `charges`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `conversations`
--
ALTER TABLE `conversations`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `deposits`
--
ALTER TABLE `deposits`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `device_tokens`
--
ALTER TABLE `device_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `education`
--
ALTER TABLE `education`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `extensions`
--
ALTER TABLE `extensions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `forms`
--
ALTER TABLE `forms`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `frontends`
--
ALTER TABLE `frontends`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=148;

--
-- AUTO_INCREMENT for table `gateways`
--
ALTER TABLE `gateways`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `gateway_currencies`
--
ALTER TABLE `gateway_currencies`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `general_settings`
--
ALTER TABLE `general_settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `job_skills`
--
ALTER TABLE `job_skills`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `languages`
--
ALTER TABLE `languages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notification_logs`
--
ALTER TABLE `notification_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notification_templates`
--
ALTER TABLE `notification_templates`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `portfolios`
--
ALTER TABLE `portfolios`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `skills`
--
ALTER TABLE `skills`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `skill_user`
--
ALTER TABLE `skill_user`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subcategories`
--
ALTER TABLE `subcategories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subscribers`
--
ALTER TABLE `subscribers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `support_attachments`
--
ALTER TABLE `support_attachments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `support_messages`
--
ALTER TABLE `support_messages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `support_tickets`
--
ALTER TABLE `support_tickets`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trial_tasks`
--
ALTER TABLE `trial_tasks`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `update_logs`
--
ALTER TABLE `update_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_logins`
--
ALTER TABLE `user_logins`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `withdrawals`
--
ALTER TABLE `withdrawals`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `withdraw_methods`
--
ALTER TABLE `withdraw_methods`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
