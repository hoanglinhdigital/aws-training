import json
import boto3
from csv import reader

s3 = boto3.client('s3')
dynamodb = boto3.resource('dynamodb')
table = dynamodb.Table('employee')

def generate_id():
    return uuid.uuid1()

def batch_insert(items, dynamodb_table):
    with dynamodb_table.batch_writer() as batch:
        for r in items:
            batch.put_item(Item=r)

def lambda_handler(event, context):
    # TODO implement
    # get the S3 bucket and file name from the event object
    bucket = event['Records'][0]['s3']['bucket']['name']
    key = event['Records'][0]['s3']['object']['key']

    # download the file from S3
    response = s3.get_object(Bucket=bucket, Key=key)
    content = response['Body'].read().decode('utf-8')
    
    rows = content.split("\n")
    
    users = list(filter(None, rows))
    
    for user in users:
        user_data = user.split(",")
        table.put_item(Item = {
            "id" : user_data[0],
            "name" : user_data[1],
            "salary" : user_data[2]
        })
    
    return {
        'statusCode': 200,
        'body': json.dumps('Successfully to processed!')
    }
