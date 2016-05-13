
#define BTN_1_LIGHT_PIN 3
#define BTN_2_LIGHT_PIN 4
#define BTN_3_LIGHT_PIN 5
#define BTN_1_SWITCH_PIN 6
#define BTN_2_SWITCH_PIN 7
#define BTN_3_SWITCH_PIN 8

int pressedButton = 0;
unsigned long blinkStart;
int blinkButtonLightPin;
boolean disableButton1 = false;
boolean disableButton2 = false;
boolean disableButton3 = false;

void setup() {
  Serial.begin(9600);
  pinMode(BTN_1_LIGHT_PIN, OUTPUT);
  pinMode(BTN_2_LIGHT_PIN, OUTPUT);
  pinMode(BTN_3_LIGHT_PIN, OUTPUT);
  pinMode(BTN_1_SWITCH_PIN, INPUT);
  pinMode(BTN_2_SWITCH_PIN, INPUT);
  pinMode(BTN_3_SWITCH_PIN, INPUT);

  blinkButton(1, 2000);
  blinkButton(2, 2000);
  blinkButton(3, 2000);
}

void loop() {
  if(Serial.available() > 0 && Serial.read() == 'g') {

    pressedButton = 0;

    if(digitalRead(BTN_1_SWITCH_PIN) == LOW) {
      disableButton1 = true;
    }
    else {
      digitalWrite(BTN_1_LIGHT_PIN, HIGH);
    }

    if(digitalRead(BTN_2_SWITCH_PIN) == LOW) {
      disableButton2 = true;
    }
    else {
      digitalWrite(BTN_2_LIGHT_PIN, HIGH);
    }

    if(digitalRead(BTN_3_SWITCH_PIN) == LOW) {
      disableButton3 = true;
    }
    else {
      digitalWrite(BTN_3_LIGHT_PIN, HIGH);
    }

    if(disableButton1 && disableButton2 && disableButton3) {
      pressedButton = -1;
    }

    while(pressedButton == 0) {
      if(digitalRead(BTN_1_SWITCH_PIN) == LOW && !disableButton1) {
        pressedButton = 1;
        digitalWrite(BTN_2_LIGHT_PIN, LOW);
        digitalWrite(BTN_3_LIGHT_PIN, LOW);
      }
      else if(digitalRead(BTN_2_SWITCH_PIN) == LOW  && !disableButton2) {
        pressedButton = 2;
        digitalWrite(BTN_1_LIGHT_PIN, LOW);
        digitalWrite(BTN_3_LIGHT_PIN, LOW);
      }
      else if(digitalRead(BTN_3_SWITCH_PIN) == LOW  && !disableButton3) {
        pressedButton = 3;
        digitalWrite(BTN_1_LIGHT_PIN, LOW);
        digitalWrite(BTN_2_LIGHT_PIN, LOW);
      }
      delay(1);
    }

    Serial.println(pressedButton);

    blinkButton(pressedButton, 3000);

    disableButton1 = false;
    disableButton2 = false;
    disableButton3 = false;
  }
}

void blinkButton(int button, int durationMillis) {
  blinkStart = millis();

  switch(button) {
    case 1:
      blinkButtonLightPin = BTN_1_LIGHT_PIN;
      break;
    case 2:
      blinkButtonLightPin = BTN_2_LIGHT_PIN;
      break;
    case 3:
      blinkButtonLightPin = BTN_3_LIGHT_PIN;
      break;
    default:
      durationMillis = 0;
  }

  while( (millis() - blinkStart) < durationMillis) {
    digitalWrite(blinkButtonLightPin, LOW);
    delay(100);
    digitalWrite(blinkButtonLightPin, HIGH);
    delay(100);
  }

  digitalWrite(blinkButtonLightPin, LOW);
}
