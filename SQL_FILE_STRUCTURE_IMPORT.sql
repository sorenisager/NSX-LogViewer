CREATE TABLE `nsxlogger_token` (
  `id` int NOT NULL,
  `token` varchar(300) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `expire` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


ALTER TABLE `nsxlogger_token`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `nsxlogger_token`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;