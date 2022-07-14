# Deployment guideline for Terraform template
1. Environment Setup on your local pc
1. Preparation
 - Checkout latest source code.
 - Manually created AWS needed resource (Because it can not created by terraform)
   - EC2 Keypair
   - SSL Certificate, import to ACM or create new one.
   - Create service linked role for ECS Service.
 - Prepare paramter for environment
2. Execution
 - Init
   Navigate to folder that associate with environment you want to deploy: 
   cd .\terraform-template\env\development\
   Check for parameter intput for your environment:
   file name: <env>.tfvars
   Execute below command
   terraform init
 - Plan
   Execute below command:
   terraform plan --var-file <env>.tfvars
 - Apply
   terraform apply --var-file <env>.tfvars
