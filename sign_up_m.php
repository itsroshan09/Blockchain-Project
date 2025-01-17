<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up for Your Dashboard</title>
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
            background-color: #007bff;
        }

        .alert {
            margin-top: 20px;
            border-radius: 10px;
            color: #721c24;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            padding: 10px;
        }
    </style>
</head>
<body>
<div id="particles-js"></div>
<div class="container">
    <div class="card">
        <h3 class="card-title">Sign Up for Your Dashboard</h3>
        <form method="post" action="signup_process.php">
            <div class="form-group">
                <label for="name">First Name:</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>

            <div class="form-group">
                <label for="lname">Last Name:</label>
                <input type="text" class="form-control" id="lname" name="lname" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label>Choose an Option:</label>
                <div class="form-check">
                    <input class="form-check-input" type="radio" id="employee" name="choice" value="Employee">
                    <label class="form-check-label" for="employee">Employee</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" id="user" name="choice" value="User">
                    <label class="form-check-label" for="user">User</label>
                </div>
            </div>

           
            <div id="employee-field"></div>

            

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
            </div>
            
            <button type="submit" class="btn btn-primary">Sign Up</button>
        </form>
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

    const employeeRadio = document.getElementById("employee");
    const userRadio = document.getElementById("user");
    const employeeField = document.getElementById("employee-field");

    employeeRadio.addEventListener("change", function () {
        if (employeeRadio.checked) {
            employeeField.innerHTML = `
                <div class="form-group">
                    <label for="employee-id">Employee ID:</label>
                    <input type="text" class="form-control" id="employee_id" name="employee_id" placeholder="Enter your Employee ID." required>
                </div>`;
        }
    });

    userRadio.addEventListener("change", function () {
        if (userRadio.checked) {
            employeeField.innerHTML = "";
        }
    });
</script>
</body>
</html>
