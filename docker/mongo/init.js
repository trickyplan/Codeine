db.createUser(
    {
        user: "codeine",
        pwd: "test-password",
        roles: [
            {
                role: "readWrite",
                db: "codeine"
            }
        ]
    }
);

db = db.getSiblingDB('codeine');