
resource "aws_iam_role" "ecs_task_exec_role" {
  name = "ecsTaskExecRole"

  assume_role_policy = <<EOF
{
  "Version": "2012-10-17",
  "Statement": [
    {
      "Action": "sts:AssumeRole",
      "Principal": {
        "Service": "ecs-tasks.amazonaws.com"
      },
      "Effect": "Allow",
      "Sid": ""
    }
  ]
}
EOF

  tags = merge(
    local.common_tags,
    tomap({
      "Name"        = "ecsTaskExecRole_${local.name}_${local.environment}",
      "Description" = "role"
    })
  )
}


resource "aws_iam_role_policy" "ecs_task_exec_role_policy" {
  name = "ecsTaskExecRolePolicy"

  role = aws_iam_role.ecs_task_exec_role.id

  policy = <<EOF
{
  "Version": "2012-10-17",
  "Statement": [
    {
      "Effect": "Allow",
      "Action": [
        "ecr:GetAuthorizationToken",
        "ecr:BatchCheckLayerAvailability",
        "ecr:GetDownloadUrlForLayer",
        "ecr:BatchGetImage",
        "logs:CreateLogStream",
        "logs:PutLogEvents",
        "logs:CreateLogGroup"
      ],
      "Resource": "*"
    }
  ]
}
EOF

}
