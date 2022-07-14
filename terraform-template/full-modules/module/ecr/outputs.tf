output "ecr_backend_main_repository_url" {
  description = "URL of backend main repository"
  value = aws_ecr_repository.ecr_backend_main_repository.repository_url
}
output "ecr_backend_sidekiq_repository_url" {
  description = "URL of backend sidekiq repository"
  value = aws_ecr_repository.ecr_backend_sidekiq_repository.repository_url
}