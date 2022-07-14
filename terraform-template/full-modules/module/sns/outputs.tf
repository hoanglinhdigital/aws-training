output "level_a_alarm_topic_arn" {
    value = aws_sns_topic.level_a_alarm_topic_01.arn
}
output "level_b_alarm_topic_arn" {
    value = aws_sns_topic.level_b_alarm_topic_01.arn
}
output "level_c_alarm_topic_arn" {
    value = aws_sns_topic.level_c_alarm_topic_01.arn
}
output "cloud_trail_alarm_topic_arn" {
    value = aws_sns_topic.cloud_trail_alarm_topic_01.arn
}