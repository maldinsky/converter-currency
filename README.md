# currency converter

## Установка
git clone <>
docker-compose up -d

Далее:
composer install

## Конфигурация

Переименовать .example.env в .env, проверить правильно конфигурации. Указать ключ api для конвертации (ключ получить можно на https://apilayer.com/).

## Структура БД:

```$xslt

CREATE TABLE `Currency` (
  `code` varchar(3) CHARACTER SET utf8 NOT NULL,
  `name` varchar(25) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `Currency` (`code`, `name`) VALUES
('BYN', 'Бел. рубль'),
('RUB', 'Рос. рубль'),
('USD', 'Доллар'),
('EUR', 'Евро');

CREATE TABLE `History` (
  `id` int(11) NOT NULL,
  `id_visitor` int(11) NOT NULL,
  `currency_to` varchar(3) CHARACTER SET utf8 NOT NULL,
  `currency_from` varchar(3) CHARACTER SET utf8 NOT NULL,
  `amount` float(15,3) NOT NULL,
  `result` varchar(255) CHARACTER SET utf8 NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `Visitor` (
  `id` int(11) NOT NULL,
  `session_code` varchar(255) CHARACTER SET utf8 NOT NULL,
  `setting` longtext CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `Currency`
  ADD PRIMARY KEY (`code`);

ALTER TABLE `History`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `Visitor`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `History`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

ALTER TABLE `Visitor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
```



