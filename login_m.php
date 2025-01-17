<?php
session_start();

include('db.php');




if (isset($_SESSION['user_email']) && isset($_SESSION['type'])) {
    if ($_SESSION['type'] == "User") {
        header('Location: user_main.php');
        exit();
    } elseif ($_SESSION['type'] == "Employee") {
        header('Location: employee_main.php');
        exit();
    }elseif($_SESSION['type']=="Admin"){
        header('Location: admin_main.php');
        exit();
    }
    
    
    exit; 
}



if ($_SERVER["REQUEST_METHOD"] == "POST") {

    
    $email = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = '$email' AND is_verified = '1'";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $storedPassword = $user['password'];

       
        if ($password === $storedPassword) {
            $_SESSION['type'] = $user['type']; 
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_id'] =$user['user_id'];

            if($user['type']=="USER"){
            header('Location: user_main.php');
            exit();
            }elseif ($_SESSION['type'] == "Employee"){
                header('Location: employee_main.php');
            exit();
            }
            
            exit; 
        } else {
            echo 'invalid_password';
        }
    } else {
        if($email == "admin@gmail.com" && $password== "Admin"){
            $_SESSION['type'] ="Admin"; 
            header('Location: admin_main.php');
            exit();
        }else{
            echo 'Sign Up or verify the email first';
        }
        
    }

    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login to Your Dashboard</title>
    <style>
        body {
            margin: 0;
            overflow: hidden;
        }

        #particles-js {
            position: fixed;
            width: 100%;
            height: 100%;
            background-color: #f8f9fa;
        }

        .container {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
        }

        .card {
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            background: rgba(255, 255, 255, 0.9);
            max-width: 500px;
            margin: auto;
            padding: 40px;
        }

        .card-title {
            color: #007bff;
            font-size: 24px;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 8px;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #ced4da;
            border-radius: 10px;
            box-sizing: border-box;
        }

        .btn-primary {
            background-color: #007bff;
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .alert {
            margin-top: 20px;
            border-radius: 10px;
            color: #721c24;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            padding: 10px;
        }

        .link {
            display: block;
            margin-top: 15px;
            font-size: 14px;
            color: #007bff;
            text-decoration: none;
        }

        .link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div id="particles-js"></div>

<div class="container">
    <div class="card">
        <h3 class="card-title">Login to Your Dashboard</h3>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-group">
                <label for="username">Username/Email</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <?php if (isset($error_message)) { ?>
                <div class="alert"><?php echo $error_message; ?></div>
            <?php } ?>
            <button type="submit" class="btn-primary">Login</button>
        </form>
        <a href="sign_up_m.php" class="link">Sign Up</a>
        <a href="forgot_m.php" class="link">Forgot Password?</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
<script>
    particlesJS('particles-js', {
        particles: {
            number: {
                value: 80,
                density: {
                    enable: true,
                    value_area: 800
                }
            },
            color: {
                value: '#000000'
            },
            shape: {
                type: 'circle',
                stroke: {
                    width: 0,
                    color: '#000000'
                },
                polygon: {
                    nb_sides: 5
                }
            },
            opacity: {
                value: 0.5,
                random: false,
                anim: {
                    enable: false,
                    speed: 1,
                    opacity_min: 0.1,
                    sync: false
                }
            },
            size: {
                value: 3,
                random: true,
                anim: {
                    enable: false,
                    speed: 40,
                    size_min: 0.1,
                    sync: false
                }
            },
            line_linked: {
                enable: true,
                distance: 150,
                color: '#000000',
                opacity: 0.4,
                width: 1
            },
            move: {
                enable: true,
                speed: 6,
                direction: 'none',
                random: false,
                straight: false,
                out_mode: 'out',
                bounce: false,
                attract: {
                    enable: false,
                    rotateX: 600,
                    rotateY: 1200
                }
            }
        },
        interactivity: {
            detect_on: 'canvas',
            events: {
                onhover: {
                    enable: true,
                    mode: 'grab'
                },
                onclick: {
                    enable: true,
                    mode: 'push'
                },
                resize: true
            },
            modes: {
                grab: {
                    distance: 140,
                    line_linked: {
                        opacity: 1
                    }
                },
                bubble: {
                    distance: 400,
                    size: 40,
                    duration: 2,
                    opacity: 8,
                    speed: 3
                },
                repulse: {
                    distance: 200,
                    duration: 0.4
                },
                push: {
                    particles_nb: 4
                },
                remove: {
                    particles_nb: 2
                }
            }
        },
        retina_detect: true
    });
</script>

</body>
</html>
