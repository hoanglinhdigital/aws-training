import json
import boto3
import botocore

#Function to prepare response as API Gateway standard.        
def prepare_response(status_code, headers, body):
    response = {
        'statusCode': status_code,
        'headers': headers,
        'body': json.dumps(body)
    }
    return response;
#Main function
def lambda_handler(event, context):
    #check event input
    print('Input from APIGateway:\n')
    print(event)
    #Init boto3
    client = boto3.client('cognito-idp')

    body = event['body']
    print('Request body:')
    print(str(event['body']))
    
    #Get client request
    body=json.loads(body)
    username = body['username']
    password = body['password']
    email = body['email']
    
    #Check email exist or not (Because cognito allow user to register the same email)
    email_existed = False
    check_response = client.list_users(
        UserPoolId='us-east-1_O52e3T9mh',
        Filter='email="'+email+'"'
    )
    
    if check_response != None:
        for user in check_response['Users']:
            if user['UserStatus'] == 'CONFIRMED':
                email_existed = True
                break
    if email_existed:
        #Response
        body = {
                'error_code':'EMAIL_AREADY_EXIST',
                'message':'Email is already exist, please choose difference email.'
                }        
        response = prepare_response(200, {}, body)
        return response

    #Register with cognito
    try:
        response = client.sign_up(
            ClientId='66u8b8fiuauo4fni0ft0cnhtem',
            Username=username,
            Password=password,
            UserAttributes=[
                {
                    'Name': 'email',
                    'Value': email
                }
            ]
        )
        #print('response from cognito: ' + str(response))
        
        #Response
        body = {
                'code':'SUCCESS',
                'message':'Successfully to register new user! Please follow email instruction to activate account.'
                }        
        response = prepare_response(200, {}, body)
        return response
        
    except client.exceptions.UsernameExistsException as e:
        print('there was some error: ' + str(e))
        #Response
        body = {
                'error_code':'USER_AREADY_EXIST',
                'message':'Email is already exist, please choose difference username.'
                }        
        response = prepare_response(400, {}, body)
        return response
        
    except client.exceptions.InvalidPasswordException as e:
        print('there was some error: ' + str(e))
        #Response
        body = {
                'error_code':'PASSWORD_NOT_MATCH_POLICY',
                'message':'Password length > 8 characters, contain alphanumeric, lowercase, uppercase, special charracter.'
                }        
        response = prepare_response(400, {}, body)
        return response
   



#README
#Client request
# {
#     "username":"string",
#     "password":"string",
#     "email":"string"
# }

#Response:
# {
#     "statusCode": number,
#     "message":"string"
# }