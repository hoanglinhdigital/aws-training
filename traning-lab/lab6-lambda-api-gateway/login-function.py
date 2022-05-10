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

def lambda_handler(event, context):
    #check event input
    print('Input from APIGateway:\n')
    print(event)
    
    #Init boto3
    client = boto3.client('cognito-idp')
    
    body = event['body']
    print('Request body:')
    print(str(event['body']))
    
    
    body=json.loads(body)
    username = body['username']
    password = body['password']
    
    print('username: ' + username)
    print('password: ' + password)
    
    #Login with cognito
    try:
        response = client.initiate_auth(
            AuthFlow='USER_PASSWORD_AUTH',
            AuthParameters={
                'USERNAME': username,
                'PASSWORD':password
            },

            ClientId='66u8b8fiuauo4fni0ft0cnhtem'
        )
        #Response
        response = prepare_response(200, {}, response)
        return response

    except client.exceptions.PasswordResetRequiredException as e:
        print('there was some error: ' + str(e))
        #Response
        body = {
                'error_code':'PASSWORD_RESET_REQUIRED',
                'message':'Must reset password before login'
                }        
        response = prepare_response(400, {}, body)
        return response
        
    except client.exceptions.UserNotFoundException as e:
        print('there was some error: ' + str(e))
        #Response
        body = {
                'error_code':'USER_NOT_EXIST',
                'message':'User not exist'
                }        
        response = prepare_response(400, {}, body)
        return response        
        
    except client.exceptions.UserNotConfirmedException as e:
        print('there was some error: ' + str(e))
        #Response
        body = {
                'error_code':'USER_NOT_CONFIRMED',
                'message':'User not confirmed yet, please follow email instruction to complete account confirmation.'
                }        
        response = prepare_response(400, {}, body)
        return response   

    except client.exceptions.NotAuthorizedException as e:
        print('there was some error: ' + str(e))
        #Response
        body = {
                'error_code':'INVALID_USERNAME_OR_PASSWORD',
                'message':'Incorrect username or password.'
                }        
        response = prepare_response(400, {}, body)
        
        return response

#README
#Client request
# {
#     "username":"string",
#     "password":"string"
# }

#Response:
# {
# {
#     "statusCode": 200,
#     "response": {
#         "ChallengeParameters": {},
#         "AuthenticationResult": {
#             "AccessToken": "eyJraWQiOiJCNWxQclJPRkZtRVZSVG5IVDlqVWhUdG1Ndk1GWmFuRGQ1YkRIMmo1VDVvPSIsImFsZyI6IlJTMjU2In0.eyJvcmlnaW5fanRpIjoiZDk3YjEwZDgtNWQ3Mi00NDc2LTk3MDQtZTBkMTI0MjJlM2YyIiwic3ViIjoiMWUxMWJmODEtY2E3NC00ODlhLTg0YzQtNzQ2NDQ2ZjNkYzllIiwiZXZlbnRfaWQiOiI1MGFjNzlmMy1kY2I0LTQ0YzgtOTgzYy1jNmRmMGQ3YzRkZjQiLCJ0b2tlbl91c2UiOiJhY2Nlc3MiLCJzY29wZSI6ImF3cy5jb2duaXRvLnNpZ25pbi51c2VyLmFkbWluIiwiYXV0aF90aW1lIjoxNjUxMjE0ODY3LCJpc3MiOiJodHRwczpcL1wvY29nbml0by1pZHAudXMtZWFzdC0xLmFtYXpvbmF3cy5jb21cL3VzLWVhc3QtMV9PNTJlM1Q5bWgiLCJleHAiOjE2NTEyMTg0NjcsImlhdCI6MTY1MTIxNDg2NywianRpIjoiNWEwZDY1YjEtM2Y1Ni00NWNlLWJkZmUtMDg0NmUyNTRmNTZjIiwiY2xpZW50X2lkIjoiNjZ1OGI4Zml1YXVvNGZuaTBmdDBjbmh0ZW0iLCJ1c2VybmFtZSI6ImxpbmhkYWljYXoyIn0.knX9ARVxvC_Roqnjv0U9dCW-6jNEvu9JVGvyDamOjJiw_0-rUlahJdqIknwbnb0dSFTEu_iWVd6HOz3TVRwrMHRwJwRwKyQvCYpKnBQlVNVIUNfSxc6Lp6hWQTE9te2-V7A0kL8fzxXAjncdBdUkeQNQQL3V5zw1LfX8HrQA7z4T7KlrO8vP3ABuCMifZJoNI81JJeUkPZ4xVlkrWkY4n9_ikzL6XvBZ_dQEVd3RjjrRI8FEt4bI-achJzgzHdoXdZZh0S4A0-XgYbWfD9h4BnSU0Q1y7qqyUW6QcyZqTVkTdaTO1X52DK_JJL7sMYXT-iyQ9Ae5OKZO8YwMPbhvOw",
#             "ExpiresIn": 3600,
#             "TokenType": "Bearer",
#             "RefreshToken": "eyJjdHkiOiJKV1QiLCJlbmMiOiJBMjU2R0NNIiwiYWxnIjoiUlNBLU9BRVAifQ.Z7F_h8WArS9FqPbq2LMiUIe1R8y5YOhO5i0TU33Q9RdcvErzAmhBU3XzdCM-k5NZv3u5yPpEXyPEkQUHjyOwaIhIdK0X0zDqO8p4Wem4BNhTNuwvY_s2PzyCGZ2h7fy-zym4xNRZEZbRYMVD_J65AhtPi8UDjRgxamyi3agNGZjP0-Asabv5Uu7sCPPMV7NVEUxHpoGjm7aNd345PSoTOzLPDnns3cGmEjaWv4rJdOtvLVaA17WqjQaMFm75yBMX5pYoxyOkUOnWzSzZRH8Oao4iDu83Zq0rBdTC3oS-uW2gPleKwDGbqR2e_zNdDq9J4BUfe00sckHA52h1hpB-cA.HqOd04CXhuP1c41s.hz2Ztl8kaz7wc295nxSkx2ATJ2ukWdjj7hsrYzJHWx4fbrTvRakr0nH3WBI2M9wWcDnARZ8bmZ4ThAgnx4QcHHui7Y-zQTw3DYceEHqQ3TYXbk9prLVHId3aQf_SJc680a0KckeC0fuKg6XvBXE5zefoO4iJf_Ts19_Rjv-AOkS24juBXwGecDAl9ssTFYzSY4EN1NyloJe0e3GMA0DLczKBnoQH3QRDf7EyQ8rZLiKcS-hydFNPRbyGF7FSXE36_C4RDOaOwU6nktILlXiIamn2nJXunIOcSrjYltGJOq0MWV0UUVbzurjS1f6agfXIb34t8uBUgaqJLys007EU17xh7Ly-WxZdpWd-l7BvnGdJC2_nBcPFJZhL1MnIsr7XkTl3jOTCXL4HeY1izOMvxgwlzpGeafExrMPaAG5719gbLIQFTHv38zmKcNftS1nBM2I2BLzTasnBWBAJBJhEP7IBVgPRsvx46oSMzeweY2JG9PQyDcIm6Gd3kP1_UJ4b4NfmvY-Fkp41vUr1LeA8zwQPFGXOWPm8TS2l2tyOvULne86IjMcuzB7u1YnS2dMOi838Zzb2HT9cSzIiXImqCYJYrAnBEKh4dwA0HFErfaaWKRnGkf68Be9Qk7_xvPg-RhDTN74r8lZhuNc4YwcJV_95yF4Dto5kSuGijzvgRe9SMMS5hC4QH2nhcu4zvItebkZ9vcvRwD4I5OwLvm8CErj_O1Z8z-in4DTBfxnbeYr5ygCxt9nKzNWkOVdMp4rBQpzT6MsTdHk6qqT_Rpimlscvy6YjR2OB4aIlZi2EHYdjdX1zfVpYzLeNGW5f9qYaaDkenbr4YioEbTLTpZvqJXw1QCMujFUslkjp_GgdWw00zP5Bnv_L6jx3cTS1Gre-KM0iJ1MgMCLGZK7aqiLV517upCiIHtvlgNViRdKHr-ZHkX3zOhACREPpyccLaqOgqIHfnOvepDIy77iSunw93wBeJVnpAQCRXTr71GkfRFWNOR3YTJrVsZTp6nEohuzaEflJxcgjdWFaMg4L54hTM9Bt9CyOGMJsv73CLQcANyXfL73kEOi5AjvnhhzMj3to6ch7JHpNXtHeCGRPws1czZPwaslX5MMO8OUPbjTI6Dy0kxadkOLK1chWqTwSs7MgxorbAeHBjAlL7wDmEAp6HrGUArNMDxRsJYqElHJLaaqSnjuj7QJgH52sqnlik_NPbnHrA5KSRhj4IU_9V2yjbO-5Gw6eN7gq53Y3-BuD-Tu346794lu2TZXOaJFsOdJVj3NdIOFsk57V-A.NtAsKtkwQt2K9YWZwzJHyw",
#             "IdToken": "eyJraWQiOiJwUHpYRHFZcmdEcUMzeDQ0cGxoUVpsXC9lUXREcGVNc1hVZ295T2ZRbkxHUT0iLCJhbGciOiJSUzI1NiJ9.eyJzdWIiOiIxZTExYmY4MS1jYTc0LTQ4OWEtODRjNC03NDY0NDZmM2RjOWUiLCJlbWFpbF92ZXJpZmllZCI6dHJ1ZSwiaXNzIjoiaHR0cHM6XC9cL2NvZ25pdG8taWRwLnVzLWVhc3QtMS5hbWF6b25hd3MuY29tXC91cy1lYXN0LTFfTzUyZTNUOW1oIiwiY29nbml0bzp1c2VybmFtZSI6ImxpbmhkYWljYXoyIiwib3JpZ2luX2p0aSI6ImQ5N2IxMGQ4LTVkNzItNDQ3Ni05NzA0LWUwZDEyNDIyZTNmMiIsImF1ZCI6IjY2dThiOGZpdWF1bzRmbmkwZnQwY25odGVtIiwiZXZlbnRfaWQiOiI1MGFjNzlmMy1kY2I0LTQ0YzgtOTgzYy1jNmRmMGQ3YzRkZjQiLCJ0b2tlbl91c2UiOiJpZCIsImF1dGhfdGltZSI6MTY1MTIxNDg2NywiZXhwIjoxNjUxMjE4NDY3LCJpYXQiOjE2NTEyMTQ4NjcsImp0aSI6ImUwMTkxZmM4LTQ4MmItNDMyZi1iYTRhLThjZGU1ZjJlY2Q4MiIsImVtYWlsIjoiaG9hbmdsaW5oZGlnaXRhbEBnbWFpbC5jb20ifQ.I7d-qPwdBILpOsbNTaiMsaDRffb7RuSsMbFvck3V7Y9g8Kxz7Q9qoDtZXLAacJ4dl4PMwRA39MKcoOLQmC6unl8o5-TenqZVUnE7njoIztAZSnrASZP6coR1JgSdx2HLnaLw92aB8Hy3Tzsf-pO9LL11AH2VCecZpZ9dh9u3aLrP6l77qfCL9iQyMIZp3Mq-b4BdTS0NjCWan-zT0r3WemIq6rZi45ZAufQzzOUuYaoup_HzotkFlfLmzaD_2aW0-fRf6ou6HPG3hTIz81o3eQ01vlCkDA21dEYTQziL7AG1yQ-WAX2yFVWTNeMD0FP5-t8Bdv5MGwN9iYXYMC9J_w"
#         },
#         "ResponseMetadata": {
#             "RequestId": "50ac79f3-dcb4-44c8-983c-c6df0d7c4df4",
#             "HTTPStatusCode": 200,
#             "HTTPHeaders": {
#                 "date": "Fri, 29 Apr 2022 06:47:47 GMT",
#                 "content-type": "application/x-amz-json-1.1",
#                 "content-length": "3977",
#                 "connection": "keep-alive",
#                 "x-amzn-requestid": "50ac79f3-dcb4-44c8-983c-c6df0d7c4df4"
#             },
#             "RetryAttempts": 0
#         }
#     }
# }
# }