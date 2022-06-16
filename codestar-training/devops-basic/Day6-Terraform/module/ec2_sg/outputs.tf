#Output
output "instance_id" {
  description = "ID of EC2 instance"
  value = aws_instance.myapp
}
output "instance_ip" {
  description  = "Public IP of EC2 Instance"
  value = aws_instance.myapp.public_ip
}