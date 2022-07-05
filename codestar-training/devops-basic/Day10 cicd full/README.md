### 1. Init
- Fork github repo
- Github connection
- ECR store Image
### 2. CI 
- CodePipeline
- IAM Role for CodePipeline
- IAM Role for Codebuild
ECR Policy:
```
{
    "Version": "2012-10-17",
    "Statement": [
        {
            "Action": [
                "ecr:BatchGetImage",
                "ecr:BatchCheckLayerAvailability",
                "ecr:CompleteLayerUpload",
                "ecr:GetDownloadUrlForLayer",
                "ecr:InitiateLayerUpload",
                "ecr:PutImage",
                "ecr:UploadLayerPart"
            ],
            "Resource": "arn:aws:ecr:ap-southeast-1:367988507966:repository/web",
            "Effect": "Allow",
            "Sid": "EcrAccess"
        },
        {
            "Action": "ecr:GetAuthorizationToken",
            "Resource": "*",
            "Effect": "Allow",
            "Sid": "EcrGetAuthorizationToken"
        }
    ]
}
```
- codebuild
### 3. CD
#### 3.1: ASG (EC2)
- IAM Role
- Launch template
- ASG

#### 3.2: Codedeploy
- IAM Role for CodeDeploy
User Data:
```
#!/bin/bash

REGION="us-east-1"
sudo yum update -y

## install docker
sudo amazon-linux-extras install -y docker
sudo usermod -a -G docker ec2-user
sudo systemctl start docker
sudo curl -L https://github.com/docker/compose/releases/latest/download/docker-compose-$(uname -s)-$(uname -m) -o /usr/local/bin/docker-compose
sudo chmod +x /usr/local/bin/docker-compose

# ## start service
sudo systemctl enable docker

## install codedeploy
sudo yum -y install ruby
sudo yum -y install wget
cd /home/ec2-user
wget https://aws-codedeploy-$REGION.s3.$REGION.amazonaws.com/latest/install
chmod +x ./install
sudo ./install auto

## start codedeploy agent. Must end of all
sudo service codedeploy-agent start

```
- CodeDeploy Application
- CodeDeploy Deployment Group 
- Add CodeDeploy to Stage of CodePipeline
 
