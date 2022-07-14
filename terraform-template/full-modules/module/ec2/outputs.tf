output "ec2_bastion_linux_arn" {
    value = aws_instance.bastion_linux_server.arn
}