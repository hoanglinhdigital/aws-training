terraform {
  required_providers {
    aws = {
      source  = "hashicorp/aws"
      version = "~> 3.0"
    }
  }
  required_version = ">= 0.14.9"
}

# 1 Create a VPC
resource "aws_vpc" "main-vpc" {
  cidr_block = var.vpc_cidr
  tags = {
    Name = join("-", [var.system_code, var.env_code, "main-vpc"])
  }
}

resource "aws_internet_gateway" "gw" {
  vpc_id = aws_vpc.main-vpc.id
  tags = {
    "Name" = join("-", [var.system_code, var.env_code, "igw-01"])
  }
}

resource "aws_eip" "nat-eip" {
  vpc = true
  depends_on = [
    aws_internet_gateway.gw
  ]
}

resource "aws_nat_gateway" "nat" {
  allocation_id = aws_eip.nat-eip.id
  subnet_id     = aws_subnet.public-subnet-1.id
  tags = {
    Name = join("-", [var.system_code, var.env_code, "nat-gw"])
  }
  depends_on = [aws_internet_gateway.gw, aws_eip.nat-eip]
}

resource "aws_subnet" "public-subnet-1" {
  vpc_id = aws_vpc.main-vpc.id
  cidr_block = var.public_subnet_cidrs[0]
  availability_zone = var.vpc_first_availability_zone
  tags = {
    "Name" = join("-", [var.system_code, var.env_code, "public-subnet-1"])
  }
}
resource "aws_subnet" "public-subnet-2" {
  vpc_id = aws_vpc.main-vpc.id
  cidr_block = var.public_subnet_cidrs[1]
  availability_zone = var.vpc_second_availability_zone
  tags = {
    "Name" = join("-", [var.system_code, var.env_code, "public-subnet-2"])
  }
}

resource "aws_subnet" "private-subnet-1" {
  vpc_id = aws_vpc.main-vpc.id
  cidr_block = var.private_subnet_cidrs[0]
  availability_zone = var.vpc_first_availability_zone
  tags = {
    "Name" = join("-", [var.system_code, var.env_code, "private-subnet-1"])
  }
}
resource "aws_subnet" "private-subnet-2" {
  vpc_id = aws_vpc.main-vpc.id
  cidr_block = var.private_subnet_cidrs[1]
  availability_zone = var.vpc_second_availability_zone
  tags = {
    "Name" = join("-", [var.system_code, var.env_code, "private-subnet-2"])
  }
}

resource "aws_subnet" "trusted-subnet-1" {
  vpc_id = aws_vpc.main-vpc.id
  cidr_block = var.trusted_subnet_cidrs[0]
  availability_zone = var.vpc_first_availability_zone
  tags = {
    "Name" = join("-", [var.system_code, var.env_code, "trusted-subnet-1"])
  }
}
resource "aws_subnet" "trusted-subnet-2" {
  vpc_id = aws_vpc.main-vpc.id
  cidr_block = var.trusted_subnet_cidrs[1]
  availability_zone = var.vpc_second_availability_zone
  tags = {
    "Name" = join("-", [var.system_code, var.env_code, "trusted-subnet-2"])
  }
}

resource "aws_subnet" "management-subnet-1" {
  vpc_id = aws_vpc.main-vpc.id
  cidr_block = var.management_subnet_cidrs[0]
  availability_zone = var.vpc_first_availability_zone
  tags = {
    "Name" = join("-", [var.system_code, var.env_code, "management-subnet-1"])
  }
}
resource "aws_subnet" "management-subnet-2" {
  vpc_id = aws_vpc.main-vpc.id
  cidr_block = var.management_subnet_cidrs[1]
  availability_zone = var.vpc_second_availability_zone
  tags = {
    "Name" = join("-", [var.system_code, var.env_code, "management-subnet-2"])
  }
}

resource "aws_route_table" "public-rtb" {
  vpc_id = aws_vpc.main-vpc.id
  route {
    cidr_block = "0.0.0.0/0"
    gateway_id = aws_internet_gateway.gw.id
  }

  tags = {
    Name = join("-", [var.system_code, var.env_code, "public-rtb"])
  }
}

resource "aws_route_table" "private-rtb" {
  vpc_id = aws_vpc.main-vpc.id

  route {
    cidr_block = "0.0.0.0/0"
    nat_gateway_id = aws_nat_gateway.nat.id
  }

  tags = {
    Name = join("-", [var.system_code, var.env_code, "private-rtb"])
  }
  depends_on = [aws_nat_gateway.nat]
}

resource "aws_route_table" "trusted-rtb" {
  vpc_id = aws_vpc.main-vpc.id

  route {
    cidr_block = "0.0.0.0/0"
    nat_gateway_id = aws_nat_gateway.nat.id
  }

  tags = {
    Name = join("-", [var.system_code, var.env_code, "trusted-rtb"])
  }
  depends_on = [aws_nat_gateway.nat]
}

resource "aws_route_table" "management-rtb" {
  vpc_id = aws_vpc.main-vpc.id

  route {
    cidr_block = "0.0.0.0/0"
    gateway_id = aws_internet_gateway.gw.id
  }

  tags = {
    Name = join("-", [var.system_code, var.env_code, "management-rtb"])
  }
  depends_on = [aws_nat_gateway.nat]
}
#Subnet association
resource "aws_route_table_association" "public-rtb-association1" {
  subnet_id = aws_subnet.public-subnet-1.id
  route_table_id = aws_route_table.public-rtb.id
}
resource "aws_route_table_association" "public-rtb-association2" {
  subnet_id = aws_subnet.public-subnet-2.id
  route_table_id = aws_route_table.public-rtb.id
}

resource "aws_route_table_association" "private-rtb-association1" {
  subnet_id = aws_subnet.private-subnet-1.id
  route_table_id = aws_route_table.private-rtb.id
}
resource "aws_route_table_association" "private-rtb-association2" {
  subnet_id = aws_subnet.private-subnet-2.id
  route_table_id = aws_route_table.private-rtb.id
}

resource "aws_route_table_association" "trusted-rtb-association1" {
  subnet_id = aws_subnet.trusted-subnet-1.id
  route_table_id = aws_route_table.trusted-rtb.id
}
resource "aws_route_table_association" "trusted-rtb-association2" {
  subnet_id = aws_subnet.trusted-subnet-2.id
  route_table_id = aws_route_table.trusted-rtb.id
}

resource "aws_route_table_association" "management-rtb-association1" {
  subnet_id = aws_subnet.management-subnet-1.id
  route_table_id = aws_route_table.management-rtb.id
}
resource "aws_route_table_association" "management-rtb-association2" {
  subnet_id = aws_subnet.management-subnet-2.id
  route_table_id = aws_route_table.management-rtb.id
}

resource "aws_security_group" "public-sg-01" {
    name = join("-", [var.system_code, var.env_code, "public-sg-01"])
    description = "Allow public traffic"
    vpc_id = aws_vpc.main-vpc.id
    tags = {
      Name = join("-", [var.system_code, var.env_code, "public-sg-01"])
    }
    ingress = [ {
      cidr_blocks = [ "0.0.0.0/0" ]
      description = "All 443"
      from_port = 443
      to_port = 443
      protocol = "TCP"
      ipv6_cidr_blocks = []
      prefix_list_ids = []
      security_groups = []
      self = false
    },
    {
      cidr_blocks = [ "0.0.0.0/0" ]
      description = "All 80"
      from_port = 80
      to_port = 80
      protocol = "TCP"
      ipv6_cidr_blocks = []
      prefix_list_ids = []
      security_groups = []
      self = false
    }]
    egress = [ {
      cidr_blocks = [ "0.0.0.0/0" ]
      description = "All outbound"
      from_port = 0
      to_port = 0
      protocol = "-1"
      ipv6_cidr_blocks = []
      prefix_list_ids = []
      security_groups = []
      self = false
    } ]
}

resource "aws_security_group" "management-sg-01" {
    name = join("-", [var.system_code, var.env_code, "management-sg-01"])
    description = "Allow traffic from Company only"
    vpc_id = aws_vpc.main-vpc.id
    tags = {
      Name = join("-", [var.system_code, var.env_code, "management-sg-01"])
    }
    ingress = [ {
      cidr_blocks = var.management_sg_allowed_cidrs
      description = "SSH"
      from_port = 22
      to_port = 22
      protocol = "TCP"
      ipv6_cidr_blocks = []
      prefix_list_ids = []
      security_groups = []
      self = false
    }
    ]
    egress = [ {
      cidr_blocks = [ "0.0.0.0/0" ]
      description = "All outbound"
      from_port = 0
      to_port = 0
      protocol = "-1"
      ipv6_cidr_blocks = []
      prefix_list_ids = []
      security_groups = []
      self = false
    } ]
}

resource "aws_security_group" "app-sg-01" {
    name = join("-", [var.system_code, var.env_code, "app-sg-01"])
    description = "Security group for App segment"
    vpc_id = aws_vpc.main-vpc.id
    tags = {
      Name = join("-", [var.system_code, var.env_code, "app-sg-01"])
    }
    ingress = [ 
    ]
    egress = [ {
      cidr_blocks = [ "0.0.0.0/0" ]
      description = "All outbound"
      from_port = 0
      to_port = 0
      protocol = "-1"
      ipv6_cidr_blocks = []
      prefix_list_ids = []
      security_groups = []
      self = false
    } ]
    depends_on = [
      aws_security_group.public-sg-01,
      aws_security_group.management-sg-01
    ]
}

# APP SG rules
resource "aws_security_group_rule" "app-sg-01-rule-01" {
  type              = "ingress"
  protocol          = "TCP"
  description = "All 22 from management"
  from_port = 22
  to_port = 22
  security_group_id = aws_security_group.app-sg-01.id
  source_security_group_id = aws_security_group.management-sg-01.id
  depends_on = [
    aws_security_group.app-sg-01
  ]
}
resource "aws_security_group_rule" "app-sg-01-rule-02" {
  type              = "ingress"
  protocol          = "TCP"
  description = "All 80 from public"
  from_port = 80
  to_port = 80
  security_group_id = aws_security_group.app-sg-01.id
  source_security_group_id = aws_security_group.public-sg-01.id
  depends_on = [
    aws_security_group.app-sg-01
  ]
}

resource "aws_security_group" "db-sg-01" {
    name = join("-", [var.system_code, var.env_code, "db-sg-01"])
    description = "Security group for DB segment"
    vpc_id = aws_vpc.main-vpc.id
    tags = {
      Name = join("-", [var.system_code, var.env_code, "db-sg-01"])
    }
    ingress = [ 
    ]
    egress = [ {
      cidr_blocks = [ "0.0.0.0/0" ]
      description = "All outbound"
      from_port = 0
      to_port = 0
      protocol = "-1"
      ipv6_cidr_blocks = []
      prefix_list_ids = []
      security_groups = []
      self = false
    } ]
    depends_on = [
      aws_security_group.public-sg-01,
      aws_security_group.management-sg-01,
      aws_security_group.app-sg-01
    ]

}
# DB SG rules
resource "aws_security_group_rule" "db-sg-01-rule-01" {
  type              = "ingress"
  protocol          = "TCP"
  description = "All Postgres SQL from APP"
  from_port = 5432
  to_port = 5432
  security_group_id = aws_security_group.db-sg-01.id
  source_security_group_id = aws_security_group.app-sg-01.id
  depends_on = [
    aws_security_group.app-sg-01
  ]
}
resource "aws_security_group_rule" "db-sg-01-rule-02" {
  type              = "ingress"
  protocol          = "TCP"
  description = "All Postgres SQL from APP"
  from_port = 6379
  to_port = 6379
  security_group_id = aws_security_group.db-sg-01.id
  source_security_group_id = aws_security_group.app-sg-01.id
  depends_on = [
    aws_security_group.app-sg-01
  ]
}
resource "aws_security_group_rule" "db-sg-01-rule-03" {
  type              = "ingress"
  protocol          = "TCP"
  description = "All Postgres SQL from MGMT"
  from_port = 5432
  to_port = 5432
  security_group_id = aws_security_group.db-sg-01.id
  source_security_group_id = aws_security_group.management-sg-01.id
  depends_on = [
    aws_security_group.app-sg-01
  ]
}
resource "aws_security_group_rule" "db-sg-01-rule-04" {
  type              = "ingress"
  protocol          = "TCP"
  description = "All Postgres SQL from MGMT"
  from_port = 6379
  to_port = 6379
  security_group_id = aws_security_group.db-sg-01.id
  source_security_group_id = aws_security_group.management-sg-01.id
  depends_on = [
    aws_security_group.app-sg-01
  ]
}
