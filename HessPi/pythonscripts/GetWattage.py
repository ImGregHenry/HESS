#from __future__ import division
import time
import os
import RPi.GPIO as GPIO
#import eeml
#import eeml.datastream
#import eeml.unit
import serial

GPIO.setwarnings(False)
GPIO.setmode(GPIO.BCM)
DEBUG = 0

 
# read SPI data from MCP3008 chip, 8 possible adc's (0 thru 7)
def readadc(adcnum, clockpin, mosipin, misopin, cspin):
        if ((adcnum > 7) or (adcnum < 0)):
                return -1
        GPIO.output(cspin, True)
 
        GPIO.output(clockpin, False)  # start clock low
        GPIO.output(cspin, False)     # bring CS low
 
        commandout = adcnum
        commandout |= 0x18  # start bit + single-ended bit
        commandout <<= 3    # we only need to send 5 bits here
        for i in range(5):
                if (commandout & 0x80):
                        GPIO.output(mosipin, True)
                else:
                        GPIO.output(mosipin, False)
                commandout <<= 1
                GPIO.output(clockpin, True)
                GPIO.output(clockpin, False)
 
        adcout = 0
        # read in one empty bit, one null bit and 10 ADC bits
        for i in range(12):
                GPIO.output(clockpin, True)
                GPIO.output(clockpin, False)
                adcout <<= 1
                if (GPIO.input(misopin)):
                        adcout |= 0x1
 
        GPIO.output(cspin, True)
        
        adcout >>= 1       # first bit is 'null' so drop it
        return adcout
 


# change these as desired - they're the pins connected from the
# SPI port on the ADC to the Cobbler
SPICLK = 18
SPIMISO = 23
SPIMOSI = 24
SPICS = 25
 
# set up the SPI interface pins
GPIO.setup(SPIMOSI, GPIO.OUT)
GPIO.setup(SPIMISO, GPIO.IN)
GPIO.setup(SPICLK, GPIO.OUT)
GPIO.setup(SPICS, GPIO.OUT)
 

#voltage_reading = readadc(current_sensor, SPICLK, SPIMOSI, SPIMISO, SPICS)

millis = int(round(time.time() * 1000))
#print mills
start_time = millis

maxValue = 0
minValue = 0


while ( (millis - start_time) < 2000 ):
	#print millis
	

	readValue = readadc(1, SPICLK, SPIMOSI, SPIMISO, SPICS)

	#print "readValue: ", readValue

	

	#print "current: ", current_sensed
	if (readValue > maxValue):
           	#/*record the maximum sensor value*/
           	maxValue = readValue
      	if (readValue < minValue):
           	#/*record the maximum sensor value*/
           	minValue = readValue

	millis = int(round(time.time() * 1000))

	result1 = ((maxValue - minValue)) # * 5.0) / 1024

#print result1

correction_factor = (450+18+28+20+50+20+14)

current_sensed = ((1000.0 * (0.0252 * (result1 - 492.0))) - correction_factor)

#print current_sensed

if (current_sensed < 107):
	current_sensed = 0

#print (result1)

#correction = 66

vrms = (result1 / 2) * 0.707
#print vrms

#m_amps = (vrms * 1000)/66
#print m_amps
#watts = m_amps*120

#print (current_sensed-30)/1000
print (current_sensed)/1000*120