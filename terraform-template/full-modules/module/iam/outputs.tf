output "ecs_task_role_name" {
    value = aws_iam_role.ecs_task_role.name
}
output "ecs_task_role_arn" {
    value = aws_iam_role.ecs_task_role.arn
}

output "ecs_task_execution_role_name" {
    value = aws_iam_role.ecs_task_execution_role.name
}
output "ecs_task_execution_role_arn" {
    value = aws_iam_role.ecs_task_execution_role.arn
}

output "ecs_auto_scaling_role_name" {
    value = aws_iam_role.ecs_auto_scaling_role.name
}
output "ecs_auto_scaling_role_arn" {
    value = aws_iam_role.ecs_auto_scaling_role.arn
}

output "ec2_bastion_role_name" {
    value = aws_iam_role.ec2_bastion_role.name
}
output "ec2_bastion_role_arn" {
    value = aws_iam_role.ec2_bastion_role.arn
}