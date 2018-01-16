# Why?
The idea behind PSSWRD is to have a universal (and compatible) method to generate single use (per service) passwords using one master password. The method can be easily scalable to increase security and usability.

# How does it work?
Using SHA512, a master Password and a secret key are hashed toghether multiple times and converted to a string from the predefined set of characters. Then a service name, a realm key and a realm2 key are added and the process is repeated between each addition. Such solution provides many possibilities of safely inputting data in different client-server scenarios.

*  Master password - the password used by an user.
*  Secret key - the key used to identify a trusted machine. Must be the same across all machines. Can be used as a fingerprint.
*  Service name - the name of the service the password is used in.
*  Realm key - can be used to limit the method to a single implementation or as an additional server-side layer of security.
*  Realm2 key - can be used as a final layer after the server-side realm key hashing.

For example master password, secret key and service hashed together can be send to the server which hashes it with the realm key, sends it back to the client and then the client hashes it with the realm2 key. The server has no information about the master password, the secret key and the generated password and the client doesn't know the realm key. So unless both parties get fully compromised the password generation process is safe. Such server can be called the realm key provider.

# How safe it is?
It is impossible to find out any keys and the master password from the generated password. Everything is hashed multiple times to make it harder to brute-force any step of the method. Even in a non-realm key implementation If someone possesses an user's secret key it is useless without the password. The same goes for the password. It is useless without the secret key. 

# Limitations, cons
One of the limitations is that it is not possible to 'change a password' for a specific service. The workaround is to change the name of the service and it will generate a new password. 
The other con is that if an user loses the password or the sercet key it is impossible to recreate them. The workaround are implementations that provides features such as storing/restoring data.
The service name formatting (e.g. Reddit, reddit, reddit.com each can give different results) can be forgotten. The workaround are implementations that save service names for the users.

# When to use it, when not to use it and how to use it
The method in the most basic form is 'good enough'. It provides some level of security. It is still better than nothing. The biggest advantage is it is easy to use and implement. I definetely don't recommend using this method in a service that provides no way to reset the password. Using it for the e-mails thanks to which it is possible to reset those passwords is also a bad idea. For everything else I highly recommend using it. Just don't forget to write the secret key down and put it somewhere safe. Also don't forget the password!

# Default values
These values can be tweaked but to keep the compatibility across different implementations they must be the same.
*  Characters to generate strings: abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()-_=+
*  Hashing algorithm: SHA512
*  Iterations of hashings on each step: 2500
*  Max password length: 18
