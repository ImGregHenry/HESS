import RPi.GPIO as GPIO
import time
GPIO.setwarnings(False)

GPIO.setmode(GPIO.BOARD)
GPIO.setup(11,GPIO.OUT)



pwm=GPIO.PWM(11,50) #pin 12 (GPIO18, 50Hz)
pwm.start(11) #start, 2% duty cycle

time.sleep(0.5)

pwm.start(4)

time.sleep(0.45)

#pwm.ChangeDutyCycle(7.5)

#time.sleep(2)

#pwm.ChangeDutyCycle(10)
#my_pwm.ChangeFrequency(1000)

#pwm.stop()
#GPIO.cleanup()
