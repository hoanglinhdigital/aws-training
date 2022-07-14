output "alb_main_alb_arn" {
  value = aws_lb.main_alb.arn
}
output "alb_main_alb_dns" {
  value = aws_lb.main_alb.dns_name
}
output "alb_backend_service_targetgroup_arn" {
  value = aws_lb_target_group.backend_main_service_target_group.arn
}