DROP TABLE IF EXISTS Archive_Folders;
DROP TABLE IF EXISTS Archive_Files;
DROP TABLE IF EXISTS Archive_ItemHierarchy;
DROP TABLE IF EXISTS Archive_Permissions;
DROP TABLE IF EXISTS Archive_ItemPermissions;

CREATE TABLE IF NOT EXISTS Archive_Folders(
  Id VARCHAR(50),
  FolderName VARCHAR(255),
  Author INT NOT NULL,
  CreatedAt DATETIME NOT NULL,
  UpdatedAt DATETIME NOT NULL,
  PRIMARY KEY (Id)
) ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS Archive_Files(
  Id VARCHAR(50),
  FileName VARCHAR(255),
  Author INT NOT NULL,
  SizeInBytes INT NOT NULL DEFAULT 0,
  Path VARCHAR(255) NOT NULL,
  MimeType VARCHAR(100) NOT NULL,
  CreatedAt DATETIME NOT NULL,
  UpdatedAt DATETIME NOT NULL,
  PRIMARY KEY (Id)
) ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS Archive_ItemHierarchy(
  ItemId VARCHAR(50),
  ParentId VARCHAR(50),
  PRIMARY KEY (ItemId)
) ENGINE=INNODB



CREATE TABLE IF NOT EXISTS Archive_ItemPermissions(
  ItemId VARCHAR(50),
  PermissionId VARCHAR(20),
  UserId INT NOT NULL,
  Approval INT NOT NULL,
  CreatedAt DATETIME NOT NULL,
  PRIMARY KEY(ItemId,PermissionId)
) ENGINE=INNODB;
