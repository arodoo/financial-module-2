-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 28-03-2025 a las 21:04:16
-- Versión del servidor: 10.11.6-MariaDB-0+deb12u1
-- Versión de PHP: 8.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `zenfamili`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `assets`
--

CREATE TABLE `assets` (
  `id` int(11) NOT NULL,
  `membre_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `purchase_value` decimal(15,2) NOT NULL,
  `current_value` decimal(15,2) NOT NULL,
  `purchase_date` date NOT NULL,
  `last_valuation_date` date NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `loan_id` int(11) DEFAULT NULL,
  `loan_amount` decimal(15,2) DEFAULT NULL,
  `loan_monthly_payment` decimal(15,2) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Volcado de datos para la tabla `assets`
--

INSERT INTO `assets` (`id`, `membre_id`, `category_id`, `name`, `description`, `purchase_value`, `current_value`, `purchase_date`, `last_valuation_date`, `location`, `notes`, `created_at`, `updated_at`, `loan_id`, `loan_amount`, `loan_monthly_payment`) VALUES
(3, 877, 4, 'Family Car', NULL, 28000.00, 19000.00, '2022-03-19', '2025-03-19', 'Home garage', 'Family vehicle in good condition', '2025-03-19 19:53:27', '2025-03-27 17:14:03', 3, 22400.00, 417.56),
(4, 877, 3, 'Stock Portfolio', 'Mixed stocks and bonds', 50000.00, 64500.00, '2021-03-19', '2025-03-19', 'ABC Broker', 'Diversified investment portfolio', '2025-03-19 19:53:27', '2025-03-19 19:53:27', NULL, NULL, NULL),
(8, 877, 8, 'aa', NULL, 22.00, 25.00, '2025-03-06', '2025-03-27', '', 'aa', '2025-03-27 17:18:55', '2025-03-28 16:01:10', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asset_categories`
--

CREATE TABLE `asset_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Volcado de datos para la tabla `asset_categories`
--

INSERT INTO `asset_categories` (`id`, `name`, `description`, `created_at`) VALUES
(1, 'Real Estate', 'Properties, land and real estate assets', '2025-03-19 19:32:15'),
(2, 'Vehicles', 'Cars, motorcycles, boats and other vehicles', '2025-03-19 19:32:15'),
(3, 'Investments', 'Stocks, bonds, mutual funds, etc.', '2025-03-19 19:32:15'),
(4, 'Cash & Savings', 'Bank accounts, emergency funds, cash', '2025-03-19 19:32:15'),
(5, 'Retirement Accounts', 'IRA, 401(k), pension plans', '2025-03-19 19:32:15'),
(6, 'Personal Property', 'Valuable items like jewelry, art, collectibles', '2025-03-19 19:32:15'),
(7, 'Business Assets', 'Business ownership, equipment, intellectual property', '2025-03-19 19:32:15'),
(8, 'Other', 'Other types of assets', '2025-03-19 19:32:15');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `depenses_fixes`
--

CREATE TABLE `depenses_fixes` (
  `id` int(11) NOT NULL,
  `membre_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `currency` varchar(10) NOT NULL DEFAULT 'USD',
  `frequency` varchar(50) NOT NULL,
  `payment_day` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `status` enum('active','inactive','cancelled') DEFAULT 'active',
  `notes` varchar(500) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Volcado de datos para la tabla `depenses_fixes`
--

INSERT INTO `depenses_fixes` (`id`, `membre_id`, `category_id`, `name`, `amount`, `currency`, `frequency`, `payment_day`, `start_date`, `end_date`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(6, 877, 2, 'food', 2000.00, 'EUR', 'quarterly', 15, '2025-03-28', '0000-00-00', 'active', '3 people', '2025-03-28 18:36:40', '2025-03-28 19:01:42'),
(2, 877, 2, 'Facture Électricité', 100.50, 'USD', 'Mensuel', 15, '2024-01-01', NULL, 'active', 'Facture d’électricité du domicile', '2025-03-28 18:02:24', '2025-03-28 18:02:24'),
(3, 877, 3, 'Abonnement Internet', 50.00, 'USD', 'Mensuel', 10, '2024-01-01', NULL, 'active', 'Abonnement Internet domestique', '2025-03-28 18:02:24', '2025-03-28 18:02:24'),
(4, 877, 4, 'Assurance Santé', 200.00, 'USD', 'Mensuel', 5, '2024-01-01', NULL, 'active', 'Prime d’assurance santé', '2025-03-28 18:02:24', '2025-03-28 18:02:24'),
(5, 877, 5, 'Abonnement Netflix', 15.99, 'USD', 'Mensuel', 20, '2024-01-01', NULL, 'active', 'Abonnement Netflix Premium', '2025-03-28 18:02:24', '2025-03-28 18:02:24'),
(7, 877, 4, 'Car', 20.00, 'EUR', 'biannual', 15, '2025-03-28', '0000-00-00', 'active', '', '2025-03-28 18:54:09', '2025-03-28 18:54:40');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `depense_categories`
--

CREATE TABLE `depense_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Volcado de datos para la tabla `depense_categories`
--

INSERT INTO `depense_categories` (`id`, `name`, `description`, `created_at`) VALUES
(1, 'Loyer', 'Paiements mensuels du loyer', '2025-03-28 18:02:13'),
(2, 'Services publics', 'Électricité, eau, gaz, etc.', '2025-03-28 18:02:13'),
(3, 'Internet & Téléphone', 'Abonnements mensuels à Internet et téléphone', '2025-03-28 18:02:13'),
(4, 'Assurances', 'Paiements pour les assurances santé, voiture, habitation', '2025-03-28 18:02:13'),
(5, 'Abonnements', 'Services de streaming, magazines, etc.', '2025-03-28 18:02:13');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `expense_categories`
--

CREATE TABLE `expense_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Volcado de datos para la tabla `expense_categories`
--

INSERT INTO `expense_categories` (`id`, `name`, `description`, `created_at`) VALUES
(1, 'Mortgage', 'Home or property loan payments', '2025-03-19 19:32:15'),
(2, 'Taxes', 'Income and property taxes', '2025-03-19 19:32:15'),
(3, 'Professional', 'Work-related expenses', '2025-03-19 19:32:15'),
(4, 'School Fees', 'Education expenses', '2025-03-19 19:32:15'),
(5, 'Regular Bills', 'Recurring monthly expenses', '2025-03-19 19:32:15'),
(6, 'Food', 'Groceries and dining', '2025-03-19 19:32:15'),
(7, 'Transportation', 'Fuel, maintenance, and public transit', '2025-03-19 19:32:15'),
(8, 'Other', 'Miscellaneous expenses', '2025-03-19 19:32:15');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `expense_transactions`
--

CREATE TABLE `expense_transactions` (
  `id` int(11) NOT NULL,
  `membre_id` int(11) NOT NULL DEFAULT 1,
  `category_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL,
  `transaction_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Volcado de datos para la tabla `expense_transactions`
--

INSERT INTO `expense_transactions` (`id`, `membre_id`, `category_id`, `amount`, `description`, `transaction_date`, `created_at`) VALUES
(1, 877, 1, 1200.00, 'Monthly mortgage payment', '2025-02-19', '2025-03-19 19:53:27'),
(2, 877, 1, 1200.00, 'Monthly mortgage payment', '2025-01-19', '2025-03-19 19:53:27'),
(3, 877, 1, 1200.00, 'Monthly mortgage payment', '2024-12-19', '2025-03-19 19:53:27'),
(4, 877, 2, 650.00, 'Quarterly taxes', '2025-01-19', '2025-03-19 19:53:27'),
(5, 877, 3, 75.00, 'Office supplies', '2025-02-19', '2025-03-19 19:53:27'),
(6, 877, 4, 500.00, 'Tuition fees', '2025-02-19', '2025-03-19 19:53:27'),
(7, 877, 5, 120.00, 'Electricity bill', '2025-02-19', '2025-03-19 19:53:27'),
(8, 877, 5, 120.00, 'Electricity bill', '2025-01-19', '2025-03-19 19:53:27'),
(9, 877, 5, 120.00, 'Electricity bill', '2024-12-19', '2025-03-19 19:53:27'),
(10, 877, 5, 45.00, 'Water bill', '2025-02-19', '2025-03-19 19:53:27'),
(11, 877, 5, 45.00, 'Water bill', '2025-01-19', '2025-03-19 19:53:27'),
(12, 877, 5, 45.00, 'Water bill', '2024-12-19', '2025-03-19 19:53:27'),
(13, 877, 5, 60.00, 'Internet subscription', '2025-02-19', '2025-03-19 19:53:27'),
(14, 877, 5, 60.00, 'Internet subscription', '2025-01-19', '2025-03-19 19:53:27'),
(15, 877, 5, 60.00, 'Internet subscription', '2024-12-19', '2025-03-19 19:53:27'),
(16, 877, 6, 450.00, 'Groceries', '2025-02-19', '2025-03-19 19:53:27'),
(17, 877, 6, 475.00, 'Groceries', '2025-01-19', '2025-03-19 19:53:27'),
(18, 877, 6, 425.00, 'Groceries', '2024-12-19', '2025-03-19 19:53:27'),
(19, 877, 6, 120.00, 'Restaurant dinner', '2025-01-19', '2025-03-19 19:53:27'),
(20, 877, 7, 80.00, 'Gasoline', '2025-02-19', '2025-03-19 19:53:27'),
(21, 877, 7, 85.00, 'Gasoline', '2025-01-19', '2025-03-19 19:53:27'),
(22, 877, 7, 75.00, 'Gasoline', '2024-12-19', '2025-03-19 19:53:27'),
(23, 877, 8, 99.00, 'Mobile phone case', '2025-02-19', '2025-03-19 19:53:27'),
(24, 877, 6, 300.00, 'Montly expenses', '2025-03-20', '2025-03-20 18:11:30'),
(25, 877, 1, 557.00, 'Updated', '2025-03-13', '2025-03-20 18:15:32'),
(26, 877, 1, 557.00, '', '2025-03-13', '2025-03-20 18:16:42');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `income_categories`
--

CREATE TABLE `income_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Volcado de datos para la tabla `income_categories`
--

INSERT INTO `income_categories` (`id`, `name`, `description`, `created_at`) VALUES
(1, 'Salary', 'Regular employment income', '2025-03-19 19:32:15'),
(2, 'Bonus', 'Performance or holiday bonuses', '2025-03-19 19:32:15'),
(3, 'Real Estate', 'Rental income from properties', '2025-03-19 19:32:15'),
(4, 'Capital Gains', 'Income from investments', '2025-03-19 19:32:15'),
(5, 'Allowances', 'Regular allowances received', '2025-03-19 19:32:15'),
(6, 'Other', 'Miscellaneous income sources', '2025-03-19 19:32:15');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `income_transactions`
--

CREATE TABLE `income_transactions` (
  `id` int(11) NOT NULL,
  `membre_id` int(11) NOT NULL DEFAULT 1,
  `category_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL,
  `transaction_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Volcado de datos para la tabla `income_transactions`
--

INSERT INTO `income_transactions` (`id`, `membre_id`, `category_id`, `amount`, `description`, `transaction_date`, `created_at`) VALUES
(1, 877, 1, 3500.00, 'Monthly Salary', '2025-02-19', '2025-03-19 19:53:27'),
(2, 877, 1, 3500.00, 'Monthly Salary', '2025-01-19', '2025-03-19 19:53:27'),
(3, 877, 1, 3500.00, 'Monthly Salary', '2024-12-19', '2025-03-19 19:53:27'),
(4, 877, 2, 1000.00, 'Year-end bonus', '2025-01-19', '2025-03-19 19:53:27'),
(5, 877, 3, 750.00, 'Apartment rental income', '2025-02-19', '2025-03-19 19:53:27'),
(6, 877, 3, 750.00, 'Apartment rental income', '2025-01-19', '2025-03-19 19:53:27'),
(7, 877, 3, 750.00, 'Apartment rental income', '2024-12-19', '2025-03-19 19:53:27'),
(8, 877, 4, 350.00, 'Stock dividends', '2024-12-19', '2025-03-19 19:53:27'),
(9, 877, 5, 200.00, 'Transportation allowance', '2025-02-19', '2025-03-19 19:53:27'),
(10, 877, 5, 200.00, 'Transportation allowance', '2025-01-19', '2025-03-19 19:53:27'),
(11, 877, 5, 200.00, 'Transportation allowance', '2024-12-19', '2025-03-19 19:53:27'),
(12, 877, 6, 120.00, 'Freelance project', '2025-02-19', '2025-03-19 19:53:27'),
(13, 877, 4, 400.00, 'Actions gains', '2025-03-20', '2025-03-20 17:33:18'),
(26, 877, 2, 900.00, 'Cody One', '2025-03-26', '2025-03-26 20:01:43'),
(27, 877, 4, 900.00, 'Salary', '2025-03-27', '2025-03-27 15:00:26'),
(25, 877, 2, 900.00, 'Tested', '2025-03-21', '2025-03-21 20:23:49');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `paiements_fixes`
--

CREATE TABLE `paiements_fixes` (
  `id` int(11) NOT NULL,
  `membre_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `currency` varchar(10) NOT NULL DEFAULT 'EUR',
  `frequency` varchar(50) NOT NULL,
  `payment_day` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `status` enum('active','inactive','cancelled') DEFAULT 'active',
  `notes` varchar(500) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Volcado de datos para la tabla `paiements_fixes`
--

INSERT INTO `paiements_fixes` (`id`, `membre_id`, `category_id`, `name`, `amount`, `currency`, `frequency`, `payment_day`, `start_date`, `end_date`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(1, 877, 1, 'Cody One', 550.00, 'EUR', 'monthly', 15, '2025-01-28', '0000-00-00', 'active', 'main entry', '2025-03-28 15:30:36', '2025-03-28 16:51:29');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `paiement_categories`
--

CREATE TABLE `paiement_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Volcado de datos para la tabla `paiement_categories`
--

INSERT INTO `paiement_categories` (`id`, `name`, `description`, `created_at`) VALUES
(1, 'Salary', 'Regular income from a job', '2025-03-27 20:27:23'),
(2, 'Investments', 'Passive income like dividends or interest', '2025-03-27 20:27:23'),
(3, 'Freelance', 'Earnings from independent work or contracts', '2025-03-27 20:27:23'),
(4, 'Grants & Subsidies', 'Government or institutional financial support', '2025-03-27 20:27:23'),
(5, 'Reimbursements', 'Refunds from various sources', '2025-03-27 20:27:23');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `assets`
--
ALTER TABLE `assets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `idx_membre_assets` (`membre_id`),
  ADD KEY `idx_loan_asset` (`loan_id`);

--
-- Indices de la tabla `asset_categories`
--
ALTER TABLE `asset_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `depenses_fixes`
--
ALTER TABLE `depenses_fixes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `membre_id` (`membre_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indices de la tabla `depense_categories`
--
ALTER TABLE `depense_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `expense_categories`
--
ALTER TABLE `expense_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `expense_transactions`
--
ALTER TABLE `expense_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `idx_expense_membre` (`membre_id`);

--
-- Indices de la tabla `income_categories`
--
ALTER TABLE `income_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `income_transactions`
--
ALTER TABLE `income_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `idx_income_membre` (`membre_id`);

--
-- Indices de la tabla `paiements_fixes`
--
ALTER TABLE `paiements_fixes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `membre_id` (`membre_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indices de la tabla `paiement_categories`
--
ALTER TABLE `paiement_categories`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `assets`
--
ALTER TABLE `assets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `asset_categories`
--
ALTER TABLE `asset_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `depenses_fixes`
--
ALTER TABLE `depenses_fixes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `depense_categories`
--
ALTER TABLE `depense_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `expense_categories`
--
ALTER TABLE `expense_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `expense_transactions`
--
ALTER TABLE `expense_transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT de la tabla `income_categories`
--
ALTER TABLE `income_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `income_transactions`
--
ALTER TABLE `income_transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT de la tabla `paiements_fixes`
--
ALTER TABLE `paiements_fixes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `paiement_categories`
--
ALTER TABLE `paiement_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
