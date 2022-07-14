output "s3_cloud_trail_log_bucket_name" {
  value = aws_s3_bucket.s3_cloud_trail_log_bucket.id
}
output "s3_cloud_trail_log_bucket_arn" {
  value = aws_s3_bucket.s3_cloud_trail_log_bucket.arn
}
output "s3_cloudfront_log_bucket_name" {
  value = aws_s3_bucket.s3_cloudfront_log_bucket.id
}
output "s3_cloudfront_log_bucket_arn" {
  value = aws_s3_bucket.s3_cloudfront_log_bucket.arn
}
output "s3_cloudfront_log_bucket_domain_name" {
  value = aws_s3_bucket.s3_cloudfront_log_bucket.bucket_domain_name
}
output "s3_cloudwatch_log_archive_bucket_name" {
  value = aws_s3_bucket.s3_cloudwatch_log_archive_bucket.id
}
output "s3_cloudwatch_log_archive_bucket_arn" {
  value = aws_s3_bucket.s3_cloudwatch_log_archive_bucket.arn
}
output "s3_website_bucket_name"{
  value = aws_s3_bucket.s3_website_bucket.id
}
output "s3_website_bucket_arn"{
  value = aws_s3_bucket.s3_website_bucket.arn
}
output "s3_website_bucket_domain_name"{
  value = aws_s3_bucket.s3_website_bucket.bucket_domain_name
}
output "s3_website_bucket_regional_domain_name"{
  value = aws_s3_bucket.s3_website_bucket.bucket_regional_domain_name
}
output "s3_website_bucket_website_endpoint"{
  value = aws_s3_bucket.s3_website_bucket.website_endpoint
}
output "s3_alb_log_bucket_name"{
  value = aws_s3_bucket.s3_alb_log_bucket.id
}