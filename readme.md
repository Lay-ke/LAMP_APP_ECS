# Containerized LAMP Application
## Table of Contents


[Project Overview](#)

[Architecture](#)

[Infrastructure Components](#)

[Application Configuration](#)

[Deployment Process](#)

[Security Considerations](#)

[Monitoring and Logging](#)

[Maintenance and Operations](#)



## Project Overview
This project implements a containerized LAMP (Linux, Apache, MySQL, PHP) stack deployed on AWS Elastic Container Service (ECS) using a Fargate launch type. The application is a PHP-based CRUD application with a MySQL database backend hosted on Amazon RDS, designed for high availability and scalability.
The infrastructure is provisioned using AWS CloudFormation, ensuring a robust and repeatable deployment process that can be version-controlled. The architecture includes:


A multi-AZ setup with public and private subnets.


An Application Load Balancer (ALB) for traffic distribution.


A highly available MySQL RDS instance.



## Architecture
The architecture consists of:


Network Layer: Custom VPC with three public subnets and two private subnets across multiple Availability Zones.


Compute Layer: ECS Fargate for running Docker containers without managing EC2 instances.


Database Layer: Multi-AZ MySQL RDS instance deployed in private subnets.


Load Balancing: ALB distributing traffic to ECS tasks.


Security: Dedicated security groups for each component, following the principle of least privilege.



## Infrastructure Components
### Networking


Custom VPC (CIDR: 192.168.0.0/16)


3 Public subnets for ALB


2 Private subnets for ECS and RDS


Internet Gateway for outbound traffic from public subnets


Route tables for controlled traffic flow


### Security Groups


Load Balancer Security Group: Allows HTTP (port 80) traffic from the internet.


Container Security Group: Allows traffic only from ALB.


RDS Security Group: Allows MySQL (port 3306) traffic only from containers.


### Database


Amazon RDS (MySQL) - db.t3.micro with 20GB storage.


Multi-AZ deployment for high availability.


Credentials securely stored in AWS Secrets Manager.


### Container Infrastructure


ECS Cluster (Fargate) - Named MyCluster


Task Definition: 1 vCPU, 3GB Memory


Custom PHP 8.2 Apache image stored in Amazon ECR


Health checks for service availability


### Load Balancing


Application Load Balancer


Deploys across two private subnets


Distributes traffic to healthy ECS tasks



## Application Configuration
### Docker Container


Custom Docker image built on PHP 8.2 with Apache.


Contains all necessary PHP extensions for MySQL connectivity.


Links: [Dockerfile](https://chatgpt.com/c/67efb164-85c0-8003-9af9-9a8e91a28982#) | [Startup Script](https://chatgpt.com/c/67efb164-85c0-8003-9af9-9a8e91a28982#)


### Database Connection
Environment variables passed securely from AWS Secrets Manager:
DB_USERNAME
DB_PASSWORD
DB_NAME
DB_HOST (RDS Proxy endpoint)


## Deployment Process
### Prerequisites


- AWS account with appropriate permissions
- AWS CLI configured
- Docker image built and pushed to ECR


### Deployment Steps

Prepare Parameters:

ClusterName, ServiceName, TaskDefinitionFamily

Image (ECR URI), TaskRoleArn

Database credentials (DBUsername, DBPassword, DBName)




Deploy CloudFormation Stack:

aws cloudformation create-stack \
  --stack-name lamp-application \
  --template-body file://lamp-stack.yaml \
  --parameters ParameterKey=ClusterName,ParameterValue=&lt;your-cluster-name&gt; \
    ParameterKey=ServiceName,ParameterValue=&lt;your-service-name&gt; \
    ParameterKey=TaskDefinitionFamily,ParameterValue=&lt;your-task-definition-name&gt; \
    ParameterKey=TaskRoleArn,ParameterValue=&lt;your-task-role-arn&gt; \
    ParameterKey=Image,ParameterValue=&lt;your-ecr-image-uri&gt; \
    ParameterKey=DBUsername,ParameterValue=&lt;username&gt; \
    ParameterKey=DBPassword,ParameterValue=&lt;password&gt; \
    ParameterKey=DBName,ParameterValue=&lt;dbname&gt; \
  --capabilities CAPABILITY_IAM


Note: Ensure task and execution roles have necessary permissions.



Monitor Deployment:

aws cloudformation describe-stacks --stack-name lamp-application


## Maintenance and Operations
### Updating the Application


Build and push a new Docker image to ECR.


Update the ECS service with the new image:


aws ecs update-service --cluster &lt;cluster-name&gt; --service &lt;service-name&gt; --force-new-deployment
